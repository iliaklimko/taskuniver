<?php

namespace common\models;

use Yii;
use creocoder\translateable\TranslateableBehavior;
use common\models\translations\PostCategoryTranslation;

/**post_category is the model class for table "{{%post_category}}".
 *
 * @property integer $id
 * @property string $name
 * @property integer $priority
 * @property string $url_alias
 *
 * @property Post[] $posts
 */
class PostCategory extends \yii\db\ActiveRecord
{
    const ALIAS_NEWS       = 'news';
    const ALIAS_SIGHT      = 'sight';
    const ALIAS_LIFEHACK   = 'lifehack';
    const ALIAS_EXPERIENCE = 'experience';

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%post_category}}';
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'translateable' => [
                'class' => TranslateableBehavior::className(),
                'translationAttributes' => ['name'],
                'translationLanguageAttribute' => 'language_code',
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['priority', 'integer'],
            ['priority', 'default', 'value' => 0],
            ['url_alias', 'trim'],
            ['url_alias', 'filter', 'filter' => 'strtolower'],
            ['url_alias', 'required'],
            [['url_alias'], 'match', 'pattern' => '/^\w[\w\-]*\w$/'],
        ];
    }

    public function transactions()
    {
        return [
            self::SCENARIO_DEFAULT => self::OP_INSERT | self::OP_UPDATE,
        ];
    }

    public function getTranslations()
    {
        return $this->hasMany(PostCategoryTranslation::className(), ['post_category_id' => 'id']);
    }


    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPosts()
    {
        return $this->hasMany(Post::className(), ['id' => 'post_id'])->viaTable('post__post_category', ['post_category_id' => 'id']);
    }
}
