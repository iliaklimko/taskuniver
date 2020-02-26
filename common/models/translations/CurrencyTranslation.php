<?php

namespace common\models\translations;

use Yii;

/**
 * This is the model class for table "{{%currency_translation}}".
 *
 * @property integer $currency_id
 * @property string $language_code
 * @property string $name
 */
class CurrencyTranslation extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%currency_translation}}';
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
