<?php

namespace common\models;

use Yii;
use mongosoft\file\UploadImageBehavior;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "{{%gallery_item}}".
 *
 * @property integer $id
 * @property string $filename
 * @property integer $post_id
 *
 * @property Post $post
 */
class GalleryItem extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%gallery_item}}';
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'timestampable' => [
                'class' => TimestampBehavior::className(),
                'updatedAtAttribute' => false,
            ],
            'uploadable' => [
                'class' => UploadImageBehavior::className(),
                'attribute' => 'filename',
                'createThumbsOnSave' => false,
                'scenarios' => ['default'],
                'path' => '@webroot/uploads/post-gallery',
                'url' => '@web/uploads/post-gallery',
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['post_id'], 'required'],
            [['post_id'], 'integer'],
            [['filename'], 'image', 'extensions' => 'jpg, jpeg, png, gif'],
            [['post_id'], 'exist', 'skipOnError' => true, 'targetClass' => Post::className(), 'targetAttribute' => ['post_id' => 'id']],
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPost()
    {
        return $this->hasOne(Post::className(), ['id' => 'post_id']);
    }
}
