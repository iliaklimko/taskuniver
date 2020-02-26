<?php

namespace common\models\translations;

use Yii;

/**
 * This is the model class for table "{{%excursion_type_translation}}".
 *
 * @property integer $excursion_type_id
 * @property string $language_code
 * @property string $name
 */
class ExcursionTypeTranslation extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%excursion_type_translation}}';
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
