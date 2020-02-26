<?php

namespace common\models\translations;

use Yii;

/**
 * This is the model class for table "target_audience_translation".
 *
 * @property integer $target_audience_id
 * @property string $language_code
 * @property string $name
 */
class TargetAudienceTranslation extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%target_audience_translation}}';
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
