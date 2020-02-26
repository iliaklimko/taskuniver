<?php

namespace common\models\translations;

use Yii;

/**
 * This is the model class for table "{{%email_template_translation}}".
 *
 * @property integer $email_template_id
 * @property string  $language_code
 * @property string  $subject
 * @property string  $body
 */
class EmailTemplateTranslation extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%email_template_translation}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['subject', 'body'], 'trim'],
            [['subject'], 'required'],
            [['body'], 'required', 'enableClientValidation' => false],
            [['subject'], 'string', 'max' => 255],
        ];
    }
}
