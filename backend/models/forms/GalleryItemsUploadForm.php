<?php

namespace backend\models\forms;

use Yii;
use yii\base\Model;
use yii\web\UploadedFile;
use common\models\GalleryItem;

class GalleryItemsUploadForm extends Model
{
    public $postId;

    /**
     * @var UploadedFile[] files uploaded
     */
    public $files;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['postId'], 'required'],
            [['postId'], 'integer'],
            [['files'], 'image', 'extensions' => 'jpg, jpeg, png, gif', 'maxFiles' => 5, 'skipOnEmpty' => false],
        ];
    }

    public function upload()
    {
        if ($this->validate()) {
            foreach ($this->files as $file) {
                $image = new GalleryItem([
                    'filename' => $file,
                    'post_id' => $this->postId,
                ]);
                $image->save(); // $image->save(false);
            }
            return true;
        }
        return false;
    }
}
