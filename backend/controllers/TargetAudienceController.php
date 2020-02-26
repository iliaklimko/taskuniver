<?php

namespace backend\controllers;

use Yii;
use common\models\TargetAudience;
use backend\models\search\TargetAudienceSearch;
use backend\controllers\base\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * TargetAudienceController implements the CRUD actions for TargetAudience model.
 */
class TargetAudienceController extends Controller
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
     * Lists all TargetAudience models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new TargetAudienceSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single TargetAudience model.
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
     * Creates a new TargetAudience model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new TargetAudience();

        foreach (Yii::$app->request->post('TargetAudienceTranslation', []) as $lang => $data) {
            foreach ($data as $attribute => $translation) {
                $model->translate($lang)->$attribute = $translation;
            }
        }

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index']);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing TargetAudience model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id, true);

        foreach (Yii::$app->request->post('TargetAudienceTranslation', []) as $lang => $data) {
            foreach ($data as $attribute => $translation) {
                $model->translate($lang)->$attribute = $translation;
            }
        }

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index']);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing TargetAudience model.
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
     * Finds the Language model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return TargetAudience the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id, $withTranslations = false)
    {
        $query = TargetAudience::find()->where(['id' => $id]);
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
}
