<?php

namespace common\models\translations;

use Yii;

/**
 * This is the model class for table "{{%sight_translation}}".
 *
 * @property integer $sight_id
 * @property string $language_code
 * @property string $name
 */
class SightTranslation extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%sight_translation}}';
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
