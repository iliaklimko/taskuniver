<?php

namespace common\models\translations;

use Yii;

/**
 * This is the model class for table "{{%country_translation}}".
 *
 * @property integer $country_id
 * @property string $language_code
 * @property string $name
 */
class CountryTranslation extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%country_translation}}';
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
