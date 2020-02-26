<?php

namespace backend\controllers;

use Yii;
use backend\controllers\base\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;
use common\models\Excursion;
use common\models\ExcursionImage;
use backend\models\search\ExcursionImageSearch;
use backend\models\forms\ExcursionImagesUploadForm;

class ExcursionImageController extends Controller
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
     * Lists all ExcursionImage models.
     * @return mixed
     */
    public function actionIndex($excursionId)
    {
        $excursion = Excursion::findOne($excursionId);
        if (!$excursion) {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
        $form = new ExcursionImagesUploadForm([
            'excursionId' => $excursionId,
        ]);

        $searchModel = new ExcursionImageSearch([
           'excursion_id' => $excursionId,
        ]);
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        if (Yii::$app->request->isPost) {
            $form->files = UploadedFile::getInstances($form, 'files');
            $form->upload();
        }

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'uploadForm' => $form,
        ]);
    }

    /**
     * Deletes an existing ExcursionImage model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @param integer $postId
     * @return mixed
     */
    public function actionDelete($id, $excursionId)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index', 'excursionId' => $excursionId]);
    }

    /**
     * Finds the ExcursionImage model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return ExcursionImage the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = ExcursionImage::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
