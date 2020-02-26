<?php

namespace backend\controllers;

use Yii;
use backend\models\User;
use backend\models\search\UserSearch;
use backend\controllers\base\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use common\models\UserGroup;
use common\models\City;
use common\models\Language;
use backend\models\search\ExcursionSearch;

/**
 * UserController implements the CRUD actions for User model.
 */
class UserController extends Controller
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
                ],
            ],
        ]);
    }

    /**
     * Lists all User models.
     * @return mixed
     */
    public function actionIndex($groupAlias = null)
    {
        $group = null;
        if ($groupAlias) {
            $group = UserGroup::findOne(['alias' => $groupAlias]);
        }

        $searchModel = new UserSearch();
        if ($group) {
            $searchModel->user_group_id = $group->id;
        }
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'userGroupList' => $this->getUserGroupList(),
        ]);
    }

    /**
     * Displays a single User model.
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
     * Creates a new User model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $group = UserGroup::findOne(['alias' => UserGroup::ALIAS_GUIDE]);
        $model = new User([
            'user_group_id' => $group->id,
        ]);
        $model->scenario = User::SCENARIO_CREATE;

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index', 'groupAlias' => $model->group->alias]);
        }
        return $this->render('create', [
            'model' => $model,
            'userGroupList' => $this->getUserGroupList(),
            'cityList' => $this->getCityList(),
            'languageList' => $this->getLanguageList(),
        ]);
    }

    /**
     * Updates an existing User model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index', 'groupAlias' => $model->group->alias]);
        }
        return $this->render('update', [
            'model' => $model,
            'userGroupList' => $this->getUserGroupList(),
            'cityList' => $this->getCityList(),
            'languageList' => $this->getLanguageList(),
        ]);
    }

    /**
     * Deletes an existing User model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    public function actionListExcursions($userId)
    {
        $searchModel = new ExcursionSearch([
            'user_id' => $userId,
        ]);
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('list-excursions', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Finds the User model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return User the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = User::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    protected function getUserGroupList()
    {
        return UserGroup::find()->all();
    }

    protected function getCityList()
    {
        return City::find()->all();
    }

    protected function getLanguageList()
    {
        return Language::find()->all();
    }
}
