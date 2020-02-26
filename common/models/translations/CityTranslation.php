<?php

namespace common\models\translations;

use Yii;

/**
 * This is the model class for table "{{%city_translation}}".
 *
 * @property integer $city_id
 * @property string $language_code
 * @property string $name
 */
class CityTranslation extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%city_translation}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'], 'trim'],
            [['name'], 'required'],
            [['name'], 'string', 'max' => 255],
            [['name'], 'unique'],
        ];
    }
}
