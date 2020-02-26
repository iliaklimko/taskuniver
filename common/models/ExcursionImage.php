<?php

namespace common\models;

use Yii;
use mongosoft\file\UploadImageBehavior;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "{{%excursion_image}}".
 *
 * @property integer $id
 * @property string $filename
 * @property integer $created_at
 * @property integer $excursion_id
 *
 * @property Excursion $excursion
 */
class ExcursionImage extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%excursion_image}}';
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
                'path' => '@webroot/uploads/excursion/{excursion.id}/images',
                'url' => '@web/uploads/excursion/{excursion.id}/images',
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['excursion_id'], 'required'],
            [['excursion_id'], 'integer'],
            [['filename'], 'image', 'extensions' => 'jpg, jpeg, png, gif'],
            [['excursion_id'], 'exist', 'skipOnError' => true, 'targetClass' => Excursion::className(), 'targetAttribute' => ['excursion_id' => 'id']],
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getExcursion()
    {
        return $this->hasOne(Excursion::className(), ['id' => 'excursion_id']);
    }
}
