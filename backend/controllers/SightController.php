<?php

namespace backend\controllers;

use Yii;
use common\models\Sight;
use common\models\City;
use backend\models\search\SightSearch;
use backend\controllers\base\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * SightController implements the CRUD actions for Sight model.
 */
class SightController extends Controller
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
     * Lists all Sight models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new SightSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Sight model.
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
     * Creates a new Sight model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Sight();

        foreach (Yii::$app->request->post('SightTranslation', []) as $lang => $data) {
            foreach ($data as $attribute => $translation) {
                $model->translate($lang)->$attribute = $translation;
            }
        }

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index']);
        } else {
            return $this->render('create', [
                'model' => $model,
                'cityList' => $this->getCityList(),
            ]);
        }
    }

    /**
     * Updates an existing Sight model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id, true);

        foreach (Yii::$app->request->post('SightTranslation', []) as $lang => $data) {
            foreach ($data as $attribute => $translation) {
                $model->translate($lang)->$attribute = $translation;
            }
        }

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index']);
        } else {
            return $this->render('update', [
                'model' => $model,
                'cityList' => $this->getCityList(),
            ]);
        }
    }

    /**
     * Deletes an existing Sight model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Sight model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Sight the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id, $withTranslations = false)
    {
        $query = Sight::find()->where(['id' => $id]);
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

    protected function getCityList()
    {
        return City::find()->all();
    }
}