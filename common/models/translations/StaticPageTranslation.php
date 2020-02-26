<?php

namespace common\models\translations;

use Yii;

/**
 * This is the model class for table "static_page_translation".
 *
 * @property integer $static_page_id
 * @property string  $language_code
 * @property string  $title
 * @property string  $body
 * @property string  $h1
 * @property string  $meta_keywords
 * @property string  $meta_description
 */
class StaticPageTranslation extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%static_page_translation}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title', 'body', 'h1', 'meta_keywords', 'meta_description'], 'trim'],
            [['title'], 'required'],
            [['body'], 'required', 'enableClientValidation' => false],
            [['title', 'h1', 'meta_keywords', 'meta_description'], 'string', 'max' => 255],
        ];
    }
}
