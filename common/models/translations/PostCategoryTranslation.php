<?php

namespace common\models\translations;

use Yii;

/**
 * This is the model class for table "post_category_translation".
 *
 * @property integer $post_category_id
 * @property string $language_code
 * @property string $name
 */
class PostCategoryTranslation extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%post_category_translation}}';
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
