<?php

namespace common\models\translations;

use Yii;

/**
 * This is the model class for table "language_translation".
 *
 * @property integer $language_id
 * @property string $language_code
 * @property string $name
 */
class LanguageTranslation extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%language_translation}}';
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
