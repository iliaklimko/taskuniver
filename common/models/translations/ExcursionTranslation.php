<?php

namespace common\models\translations;

use Yii;

/**
 * This is the model class for table "{{%excursion_translation}}".
 *
 * @property integer $excursion_id
 * @property string $language_code
 * @property string $title
 * @property string $description
 * @property string $included_in_price
 * @property string $not_included_in_price
 * @property string $meeting_point
 * @property string $additional_info
 */
class ExcursionTranslation extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%excursion_translation}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title', 'description', 'included_in_price', 'not_included_in_price', 'meeting_point', 'additional_info'], 'trim'],
            [['title', 'description', 'included_in_price', 'not_included_in_price', 'meeting_point', 'additional_info'], 'required', 'enableClientValidation' => false],
            [['title'], 'string', 'max' => 255],
            [['title'], 'unique', 'targetAttribute' => ['title', 'language_code']],
        ];
    }

    public function attributeLabels()
    {
        return [
            'title' => Yii::t('app', 'Title'),
            'description' => Yii::t('app', 'Description'),
            'current_price' => Yii::t('app', 'Current Price'),
            'old_price' => Yii::t('app', 'Old Price'),
            'included_in_price' => Yii::t('app', 'Included In Price'),
            'not_included_in_price' => Yii::t('app', 'Not Included In Price'),
            'meeting_point' => Yii::t('app', 'Meeting Point'),
            'additional_info' => Yii::t('app', 'Additional Info'),
        ];
    }
}
