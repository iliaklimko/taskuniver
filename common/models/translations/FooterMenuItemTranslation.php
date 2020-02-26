<?php

namespace common\models\translations;

use Yii;

/**
 * This is the model class for table "{{%footer_menu_item_translation}}".
 *
 * @property integer $footer_menu_item_id
 * @property string  $language_code
 * @property string  $url
 * @property string  $title
 */
class FooterMenuItemTranslation extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%footer_menu_item_translation}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title', 'url'], 'trim'],
            [['title', 'url'], 'required'],
            [['title'], 'string', 'max' => 255],
            ['url', 'url'],
        ];
    }
}
