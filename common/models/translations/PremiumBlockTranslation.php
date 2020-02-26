<?php

namespace common\models\translations;

use Yii;

/**
 * This is the model class for table "{{%premium_block_translation}}".
 *
 * @property integer $premium_block_id
 * @property string  $language_code
 * @property string  $link_url
 * @property string  $link_label
 * @property string  $title
 * @property string  $subtitle
 */
class PremiumBlockTranslation extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%premium_block_translation}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title', 'subtitle', 'link_url', 'link_label'], 'trim'],
            [['subtitle'], 'required'],
            [['title', 'subtitle', 'link_label'], 'string', 'max' => 255],
            ['link_url', 'url'],
        ];
    }
}
