<?php

namespace backend\controllers;

use Yii;
use backend\controllers\base\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;
use backend\models\Post;
use common\models\GalleryItem;
use backend\models\search\GalleryItemSearch;
use backend\models\forms\GalleryItemsUploadForm;

class GalleryItemController extends Controller
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
     * Lists all GalleryItem models.
     * @return mixed
     */
    public function actionIndex($postId)
    {
        $post = Post::findOne($postId);
        if (!$post) {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
        $form = new GalleryItemsUploadForm([
            'postId' => $postId,
        ]);

        $searchModel = new GalleryItemSearch([
           'post_id' => $postId,
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
     * Deletes an existing GalleryItem model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @param integer $postId
     * @return mixed
     */
    public function actionDelete($id, $postId)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index', 'postId' => $postId]);
    }

    /**
     * Finds the GalleryItem model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return GalleryItem the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = GalleryItem::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
