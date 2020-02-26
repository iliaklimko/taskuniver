<?php

namespace backend\controllers;

use Yii;
use common\models\ExcursionGroups;
use backend\models\search\ExcursionGroupsSearch;
use backend\controllers\base\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * ExcursionGroupsController implements the CRUD actions for ExcursionGroups model.
 */
class ExcursionGroupsController extends Controller
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
     * Lists all ExcursionGroups models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ExcursionGroupsSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single ExcursionGroups model.
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
     * Creates a new ExcursionGroups model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new ExcursionGroups();

        foreach (Yii::$app->request->post('ExcursionGroupsTranslation', []) as $lang => $data) {
            foreach ($data as $attribute => $translation) {
                $model->translate($lang)->$attribute = $translation;
            }
        }

        $post = Yii::$app->request->post();
        if (!empty($post['ExcursionGroups']['code'])) {
            $post['ExcursionGroups']['code'] = strtolower(preg_replace('/\s+/', '-', trim($post['ExcursionGroups']['code'])));

            if ($model->load($post) && $model->save()) {
                return $this->redirect(['index']);
            } else {
                return $this->render('create', [
                    'model' => $model,
                ]);
            }
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing ExcursionGroups model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id, true);

        foreach (Yii::$app->request->post('ExcursionGroupsTranslation', []) as $lang => $data) {
            foreach ($data as $attribute => $translation) {
                $model->translate($lang)->$attribute = $translation;
            }
        }

        $post = Yii::$app->request->post();
        if (!empty($post['ExcursionGroups']['code'])) {
            $post['ExcursionGroups']['code'] = strtolower(preg_replace('/\s+/', '-', trim($post['ExcursionGroups']['code'])));

            if ($model->load($post) && $model->save()) {
                return $this->redirect(['index']);
            } else {
                return $this->render('update', [
                    'model' => $model,
                ]);
            }
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing ExcursionGroups model.
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
     * @return ExcursionGroups the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id, $withTranslations = false)
    {
        $query = ExcursionGroups::find()->where(['id' => $id]);
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
