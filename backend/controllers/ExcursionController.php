<?php

namespace backend\controllers;

use Yii;
use backend\models\Excursion;
use backend\models\search\ExcursionSearch;
use backend\controllers\base\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;
use common\models\User;
use common\models\TargetAudience;
use common\models\City;
use common\models\Sight;
use common\models\ExcursionType;
use common\models\ExcursionTheme;
use common\models\ExcursionGroups;
use common\models\Language;
use common\models\Currency;
use common\eventing\ExcursionEvents;
use common\eventing\events\ExcursionCreatedByAdminEvent;
use backend\models\forms\ExcursionImagesUploadForm;

/**
 * ExcursionController implements the CRUD actions for Excursion model.
 */
class ExcursionController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return array_merge(parent::behaviors(), [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                    'accept' => ['POST'],
                    'reject' => ['POST'],
                ],
            ],
        ]);
    }

    /**
     * Lists all Excursion models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ExcursionSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Excursion model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Excursion model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($langCode = 'ru')
    {
        $model = new Excursion([
            'status' => Excursion::STATUS_ACCEPTED,
        ]);
        $uploadForm = new ExcursionImagesUploadForm();

        foreach (Yii::$app->request->post('ExcursionTranslation', []) as $lang => $data) {
            foreach ($data as $attribute => $translation) {
                $model->translate($lang)->$attribute = $translation;
            }
        }

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->trigger(
                ExcursionEvents::EXCURSION_CREATED_BY_ADMIN,
                new ExcursionCreatedByAdminEvent([
                    'excursionId' => $model->id,
                ])
            );
            $uploadForm->excursionId = $model->id;
            $uploadForm->files = UploadedFile::getInstances($uploadForm, 'files');
            $uploadForm->upload();
            return $this->redirect(['index']);
        } else {
            return $this->render('create', [
                'model' => $model,
                'uploadForm' => $uploadForm,
                'userList' => $this->getUserList(),
                'targetAudienceList' => $this->getTargetAudienceList(),
                'cityList' => $this->getCityList(),
                'excursionTypeList' => $this->getExcursionTypeList(),
                'excursionThemeList' => $this->getExcursionThemeList(),
                'sightList' => $this->getSightList(),
                'languageList' => $this->getLanguageList(),
                'excursionList' => $this->getExcursionList(),
                'currencyList' => $this->getCurrencyList(),
                'excursionGroupsList' => $this->getExcursionGroups(),
            ]);
        }
    }

    /**
     * Updates an existing Excursion model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id, $langCode = 'ru')
    {
        $model = $this->findModel($id, true);

        foreach (Yii::$app->request->post('ExcursionTranslation', []) as $lang => $data) {
            foreach ($data as $attribute => $translation) {
                $model->translate($lang)->$attribute = $translation;
            }
        }

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index']);
        } else {
            return $this->render('update', [
                'model' => $model,
                'userList' => $this->getUserList(),
                'targetAudienceList' => $this->getTargetAudienceList(),
                'cityList' => $this->getCityList(),
                'excursionTypeList' => $this->getExcursionTypeList(),
                'excursionThemeList' => $this->getExcursionThemeList(),
                'sightList' => $this->getSightList(),
                'languageList' => $this->getLanguageList(),
                'excursionList' => $this->getExcursionList($model->id),
                'currencyList' => $this->getCurrencyList(),
                'excursionGroupsList' => $this->getExcursionGroups(),
            ]);
        }
    }

    /**
     * Deletes an existing Excursion model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    public function actionAccept($id)
    {
        $model = $this->findModel($id);
        $model->accept();
        return $this->redirect(['index']);
    }

    public function actionReject($id)
    {
        $model = $this->findModel($id);
        $model->scenario = Excursion::SCENARIO_REJECTION;
        $model->reject(Yii::$app->request->post('reason'));
        return $this->redirect(['index']);
    }

    /**
     * Finds the Language model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Excursion the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id, $withTranslations = false)
    {
        $query = Excursion::find()->where(['id' => $id]);
        if ($withTranslations) {
            $query->with('translations');
        }
        $model = $query->one();
        if ($model) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    protected function getUserList()
    {
        return User::find()->all();
    }

    protected function getTargetAudienceList()
    {
        return TargetAudience::find()
            ->with('translations')
            ->andWhere(['not', ['alias' => TargetAudience::ALIAS_ALL]])
            ->all();
    }

    protected function getCityList()
    {
        return City::find()
            ->with('translations')
            ->all();
    }

    protected function getExcursionTypeList()
    {
        return ExcursionType::find()
            ->with('translations')
            ->all();
    }

    protected function getExcursionThemeList()
    {
        return ExcursionTheme::find()
            ->with('translations')
            ->all();
    }

    protected function getSightList()
    {
        return Sight::find()
            ->with('translations')
            ->all();
    }

    protected function getLanguageList()
    {
        return Language::find()
            ->with('translations')
            ->all();
    }

    protected function getExcursionList($selfId = null)
    {
        $query = Excursion::find()
            ->andWhere(['not', ['user_id' => null] ])
            ->andWhere(['status' => Excursion::STATUS_ACCEPTED]);
        if ($selfId) {
            $query->andWhere(['<>', 'id', $selfId]);
        }
        return $query->all();
    }

    protected function getCurrencyList()
    {
        return Currency::find()
            ->with('translations')
            ->all();
    }

    protected function getExcursionGroups()
    {
        return ExcursionGroups::find()->with('translations')->all();
    }
}
