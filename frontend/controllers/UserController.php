<?php

namespace frontend\controllers;

use Yii;
use yii\web\NotFoundHttpException;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use common\models\User;
use common\models\UserGroup;
use common\models\City;
use common\models\Sight;
use common\models\Language;
use common\models\Currency;
use frontend\models\Excursion;
use common\models\ExcursionImage;
use common\models\ExcursionType;
use common\models\ExcursionTheme;
use common\models\TargetAudience;
use frontend\models\ProfileForm;
use yii\data\ActiveDataProvider;
use backend\models\forms\ExcursionImagesUploadForm;
use yii\web\UploadedFile;
use common\eventing\ExcursionEvents;
use common\eventing\events\ExcursionCreatedEvent;
use common\models\Order;
use frontend\models\ConfirmationForm;
use frontend\models\RenouncementForm;
use common\models\ExcursionGroups;

class UserController extends BaseController
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => [
                    'edit-profile',
                    'list-excursions',
                    'create-excursion',
                    'update-excursion',
                    'list-orders',
                    // 'remove-avatar',
                ],
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'remove-avatar' => ['POST'],
                    'remove-excursion-featured-image' => ['POST'],
                    'delete-excursion' => ['POST'],
                    'remove-excursion-image' => ['POST'],
                ],
            ],
        ];
    }

    public function init()
    {
        parent::init();
        $user = Yii::$app->user->identity;
        $userId = $user->id;
        list($allOrdersCount, $newOrdersCount) = $this->getUserOrdersCount($userId);
        $this->view->params['showListOrders']     = $allOrdersCount > 0;
        $this->view->params['newOrdersCount']     = $newOrdersCount;
        $this->view->params['showListExcursions'] = $user->excursions;
    }

    protected function getUserOrdersCount($id)
    {
        $query = Order::find()
            ->joinWith([
                'excursion',
            ])
            ->andWhere(['{{%order}}.status' => Order::STATUS_CHARGED])
            ->andWhere(['{{%excursion}}.user_id' => $id]);
        $queryForNew = clone $query;
        $queryForNew->andWhere(['guide_status' => Order::GUIDE_STATUS_NEW]);
        return [
            $query->count(), // all
            $queryForNew->count(), // new
        ];
    }

    public function beforeAction($action)
    {
        if (!parent::beforeAction($action)) {
            return false;
        }
        if (in_array($action->id, ['edit-profile', 'list-excursions', 'list-orders'])) {
            $this->layout = 'office';
        }
        if (in_array($action->id, ['create-excursion', 'update-excursion'])) {
            $this->layout = 'create-excursion';
        }
        return true;
    }

    public function actionListOrders()
    {
        $dataProvider = $this->findUserOrders(Yii::$app->user->id);
        if ($dataProvider->totalCount == 0) {
            return $this->redirect([
                'edit-profile',
                'locale' => Yii::$app->language != 'ru' ? Yii::$app->language : null,
            ]);
        }
        $confirmation = new ConfirmationForm();
        $renouncement = new RenouncementForm();
        if ($confirmation->load(Yii::$app->request->post()) && $confirmation->send()) {
            return $this->refresh();
        }
        if ($renouncement->load(Yii::$app->request->post()) && $renouncement->send()) {
            return $this->refresh();
        }
        return $this->render('list-orders', [
            'dataProvider' => $dataProvider,
            'confirmation' => $confirmation,
            'renouncement' => $renouncement,
        ]);
    }

    public function actionListExcursions()
    {
        $dataProvider = $this->findUserExcursions(Yii::$app->user->id);
        if ($dataProvider->totalCount > 0) {
            return $this->render('list-excursions', [
                'dataProvider' => $dataProvider,
            ]);
        }
        return $this->redirect(['edit-profile', 'locale' => Yii::$app->language]);
    }

    public function actionCreateExcursion($lang = null)
    {

        if (!$lang) {
            $lang = Yii::$app->user->identity->interface_language;
        }

        $model = new Excursion([
            'user_id' => Yii::$app->user->id,
        ]);

        $model->detachBehavior('taggable');
        $model->attachBehavior('taggableAsString', [
            'class' => \common\components\behaviors\TaggableBehavior::className(),
        ]);
        $user = Yii::$app->user->identity;
        $uploadForm = new ExcursionImagesUploadForm();

        foreach (Yii::$app->request->post('ExcursionTranslation', []) as $lang => $data) {
            foreach ($data as $attribute => $translation) {
                $model->translate($lang)->$attribute = $translation;
            }
        }


        if ($model->load(Yii::$app->request->post()) && $model->save()) {

            Yii::$app->trigger(
                ExcursionEvents::EXCURSION_CREATED,
                new ExcursionCreatedEvent([
                    'excursionId' => $model->id,
                ])
            );
            $uploadForm->excursionId = $model->id;
            $uploadForm->files = UploadedFile::getInstances($uploadForm, 'files');
            $uploadForm->upload();
            return $this->redirect([
                'list-excursions',
                'locale' => $user->interface_language != 'ru' ? $user->interface_language : null,
                '#' => 'created'
            ]);
        }

        return $this->render('create-excursion', [
            'lang' => $lang,
            'model' => $model,
            'uploadForm' => $uploadForm,
            'cityList' => $this->getCityList(),
            'sightList' => $this->getSightList(),
            'languageList' => $this->getLanguageList(),
            'currencyList' => $this->getCurrencyList(),
            'excursionTypeList' => $this->getExcursionTypeList(),
            'excursionThemeList' => $this->getExcursionThemeList(),
            'targetAudienceList' => $this->getAudienceList(),
            'excursionGroups' => $this->getExcursionGroups(),
        ]);
    }

    public function actionUpdateExcursion($id, $lang = null)
    {
        $arrPostExcursion = Yii::$app->request->post('Excursion');
        $model = $this->findExcursion($id);
        $model->detachBehavior('taggable');
        $model->attachBehavior('taggableAsString', [
            'class' => \common\components\behaviors\TaggableBehavior::className(),
        ]);


        $currentUserId = Yii::$app->user->id;
        if ($model->user->id != $currentUserId) {
            throw new \yii\web\ForbiddenHttpException();
        }

        $model->updated_by_owner = true;
        $uploadForm = new ExcursionImagesUploadForm([
            'excursionId' => $model->id,
        ]);

        foreach (Yii::$app->request->post('ExcursionTranslation', []) as $lang => $data) {
            foreach ($data as $attribute => $translation) {
                $model->translate($lang)->$attribute = $translation;
            }
        }


        if ($model->load(Yii::$app->request->post()) && $model->save()) {

            $arrDaysAndCount = array(
                'monday' => array('active' => $arrPostExcursion['mondayDays'] , 'count' => $arrPostExcursion['monday']),
                'tuesday' => array('active' => $arrPostExcursion['tuesdayDays'] , 'count' => $arrPostExcursion['tuesday']),
                'wednesday' => array('active' => $arrPostExcursion['wednesdayDays'] , 'count' => $arrPostExcursion['wednesday']),
                'thursday' => array('active' => $arrPostExcursion['thursdayDays'] , 'count' => $arrPostExcursion['thursday']),
                'friday' => array('active' => $arrPostExcursion['fridayDays'] , 'count' => $arrPostExcursion['friday']),
                'saturday' => array('active' => $arrPostExcursion['saturdayDays'] , 'count' => $arrPostExcursion['saturday']),
                'sunday' => array('active' => $arrPostExcursion['sundayDays'] , 'count' => $arrPostExcursion['sunday']),
            );

            $arrDaysAndCountSerialize = serialize($arrDaysAndCount);
            $model->date_array = $arrDaysAndCountSerialize;

            $uploadForm->files = UploadedFile::getInstances($uploadForm, 'files');
            $uploadForm->upload();
            $group = ExcursionGroups::find()->select('code')->where(['id' => $model->group_id])->one();
            return $this->redirect([
                'excursion/view',
                'locale' => $lang != 'ru' ? $lang : null,
                'excursion_id' => $model->id,
                'group_code' => $arrPostExcursion['group_code']

            ]);
        }

        return $this->render('update-excursion', [
            'lang' => $lang,
            'model' => $model,
            'uploadForm' => $uploadForm,
            'cityList' => $this->getCityList(),
            'sightList' => $this->getSightList(),
            'languageList' => $this->getLanguageList(),
            'currencyList' => $this->getCurrencyList(),
            'excursionTypeList' => $this->getExcursionTypeList(),
            'excursionThemeList' => $this->getExcursionThemeList(),
            'targetAudienceList' => $this->getAudienceList(),
            'excursionGroups' => $this->getExcursionGroups(),
        ]);
    }

    public function actionDeleteExcursion($id)
    {
        $this->findExcursion($id)->delete();

        return $this->redirect(['list-excursions', 'locale' => Yii::$app->language]);
    }

    public function actionRemoveExcursionImage($id)
    {
        if (($model = ExcursionImage::findOne($id)) !== null) {
            $model->delete();
        }
    }

    protected function findExcursion($id)
    {
        if (($model = Excursion::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionEditProfile()
    {
        $group = UserGroup::findOne(['alias' => UserGroup::ALIAS_GUIDE]);
        $id = Yii::$app->user->id;

        $model = ProfileForm::findOne($id);
        $model->user_group_id = $group->id;

        if ($model->load(Yii::$app->request->post()) && $model->updateProfile()) {
            return $this->redirect([
                    'edit-profile',
                    'locale' => $model->interface_language != 'ru' ? $model->interface_language : null,
                    '#' => 'success-msg'
            ]);
        }
        return $this->render('edit-profile', [
            'model' => $model,
            'cityList' => $this->getCityList(),
            'languageList' => $this->getLanguageList(),
        ]);
    }

    public function actionViewProfile($id)
    {
        return $this->render('view-profile', [
            'model' => $this->findModel($id),
        ]);
    }

    public function actionRemoveAvatar()
    {
        $request = Yii::$app->request;
        $modelId = $request->post('userId');
        User::updateAll(['avatar' => null], ['id' => $modelId]);
    }
    public function actionRemoveExcursionFeaturedImage()
    {
        $request = Yii::$app->request;
        $modelId = $request->post('excursionId');
        Excursion::updateAll(['featured_image' => null], ['id' => $modelId]);
    }

    protected function findUserOrders($userId)
    {
        $query = Order::find()
            ->joinWith([
                'excursion',
            ])
            ->andWhere(['{{%order}}.status' => Order::STATUS_CHARGED])
            ->andWhere(['{{%excursion}}.user_id' => $userId])
            ->orderBy('{{order}}.created_at DESC');
        return new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 10,
            ],
        ]);
    }

    protected function findUserExcursions($userId)
    {
        $query = Excursion::find()
            ->with(['translations'])
            ->andWhere(['user_id' => $userId])
            ->orderBy('created_at DESC');
        return new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 9,
            ],
        ]);
    }

    protected function findModel($id)
    {
        if (($model = User::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    protected function getCityList()
    {
        return City::find()
            ->joinWith('translations')
            ->with('translations')
            ->andWhere(['{{%city_translation}}.language_code' => Yii::$app->language])
            ->orderBy('{{%city_translation}}.name ASC')
            ->all();
    }

    protected function getSightList()
    {
        return Sight::find()
            ->joinWith('translations')
            ->with('translations')
            ->andWhere(['{{%sight_translation}}.language_code' => Yii::$app->language])
            ->orderBy('{{%sight_translation}}.name ASC')
            ->all();
    }

    protected function getLanguageList()
    {
        return Language::find()
            ->joinWith('translations')
            ->with('translations')
            ->andWhere(['{{%language_translation}}.language_code' => Yii::$app->language])
            ->orderBy('{{%language_translation}}.name ASC')
            ->all();
    }

    protected function getExcursionGroups()
    {
        return ExcursionGroups::find()
            ->joinWith('translations')
            ->all();
    }

    protected function getCurrencyList()
    {
        return Currency::find()
            ->with('translations')
            ->all();
    }

    protected function getExcursionTypeList()
    {
        return ExcursionType::find()
            ->joinWith('translations')
            ->with('translations')
            ->andWhere(['{{%excursion_type_translation}}.language_code' => Yii::$app->language])
            ->orderBy('{{%excursion_type_translation}}.name ASC')
            ->all();
    }

    protected function getExcursionThemeList()
    {
        return ExcursionTheme::find()
            ->joinWith('translations')
            ->with('translations')
            ->andWhere(['{{%excursion_theme_translation}}.language_code' => Yii::$app->language])
            ->orderBy('{{%excursion_theme_translation}}.name ASC')
            ->all();
    }

    protected function getAudienceList()
    {
        return TargetAudience::find()
            ->with('translations')
            ->indexBy('alias')
            ->all();
    }

    protected function getExcursion_groups()
    {
        return ExcursionGroups::find()->with('translations')->all();
    }
}
