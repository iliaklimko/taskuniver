<?php

namespace common\models\translations;

use Yii;

/**
 * This is the model class for table "{{%excursion_groups_translation}}".
 *
 * @property integer $excursion_groups_id
 * @property string $language_code
 * @property string $name
 */
class ExcursionGroupsTranslation extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%excursion_groups_translation}}';
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
