<?php

namespace common\models\translations;

use Yii;

/**
 * This is the model class for table "{{%premium_block_translation}}".
 *
 * @property integer $main_benefit_id
 * @property string  $language_code
 * @property string  $title
 * @property string  $body
 */
class MainBenefitTranslation extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%main_benefit_translation}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title', 'body'], 'trim'],
            [['title', 'body'], 'required'],
        ];
    }
}
