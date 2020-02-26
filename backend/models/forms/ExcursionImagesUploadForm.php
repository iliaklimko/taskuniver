<?php

namespace backend\models\forms;

use Yii;
use yii\base\Model;
use yii\web\UploadedFile;
use common\models\ExcursionImage;

class ExcursionImagesUploadForm extends Model
{
    public $excursionId;

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
            [['excursionId'], 'required'],
            [['excursionId'], 'integer'],
            [['files'], 'image', 'extensions' => 'jpg, jpeg, png, gif', 'maxFiles' => 5, 'skipOnEmpty' => true],
        ];
    }

    public function upload()
    {
        if ($this->validate()) {
            foreach ($this->files as $file) {
                $image = new ExcursionImage([
                    'filename' => $file,
                    'excursion_id' => $this->excursionId,
                ]);
                $image->save(); // $image->save(false);
            }
            return true;
        }
        return false;
    }
}
