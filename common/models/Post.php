<?php

namespace common\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii2tech\ar\linkmany\LinkManyBehavior;
use common\components\behaviors\TaggableBehavior;
use mongosoft\file\UploadImageBehavior;
use common\components\behaviors\Slug as SluggableBehavior;
use common\models\query\PostQuery;
use common\components\validators\TagValuesUniqueValidator;

/**
 * This is the model class for table "{{%post}}".
 *
 * @property integer $id
 * @property string $image
 * @property string $language_code
 * @property string $title
 * @property string $body
 * @property string $url_alias
 * @property string $h1
 * @property string $meta_keywords
 * @property string $meta_description
 * @property integer $created_at
 * @property integer $updated_at
 * @property string $publication_date
 * @property boolean $contains_images
 * @property boolean $contains_videos
 * @property integer $status
 * @property string $rejection_reason
 * @property string $user_name
 *
 * @property PostCategory[] $postCategories
 * @property TargetAudience[] $targetAudiences
 * @property PostSubject $postSubject
 * @property GalleryItem[] $galleryItems
 * @property User $author
 * @property Post[] $similarPosts
 */
class Post extends \yii\db\ActiveRecord
{
    const STATUS_NEW = 0;
    const STATUS_ACCEPTED = 10;
    const STATUS_REJECTED = 20;

    const SCENARIO_REJECTION = 'rejection';

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%post}}';
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            TimestampBehavior::className(),
            'linkPostCategoryBehavior' => [
                'class' => LinkManyBehavior::className(),
                'relation' => 'postCategories', // relation, which will be handled
                'relationReferenceAttribute' => 'postCategoryIds', // virtual attribute, which is used for related records specification
            ],
            'linkTargetAudienceBehavior' => [
                'class' => LinkManyBehavior::className(),
                'relation' => 'targetAudiences', // relation, which will be handled
                'relationReferenceAttribute' => 'targetAudienceIds', // virtual attribute, which is used for related records specification
            ],
            'linkSimilarPostBehavior' => [
                'class' => LinkManyBehavior::className(),
                'relation' => 'similarPosts', // relation, which will be handled
                'relationReferenceAttribute' => 'similarPostIds', // virtual attribute, which is used for related records specification
            ],
            'taggable' => [
                'class' => TaggableBehavior::className(),
                'tagValuesAsArray' => true,
            ],
            'uploadable' => [
                'class' => UploadImageBehavior::className(),
                'attribute' => 'image',
                'createThumbsOnSave' => false,
                'scenarios' => ['default'],
                'path' => '@webroot/uploads/post/{id}',
                'url' => '@web/uploads/post/{id}',
            ],
            'sluggable' => [
                'class' => SluggableBehavior::className(),
                'slugAttribute' => 'url_alias',
                'attribute' => 'title',
                // optional params
                'ensureUnique' => true,
                'replacement' => '-',
                'lowercase' => true,
                'immutable' => true,
                // If intl extension is enabled, see http://userguide.icu-project.org/transforms/general.
                'transliterateOptions' => 'Russian-Latin/BGN; Any-Latin; Latin-ASCII; NFD; [:Nonspacing Mark:] Remove; NFC;'
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['user_name', 'trim'],
            ['user_name', 'required'],
            [['image'], 'image', 'extensions' => 'jpg, jpeg, png, gif', 'skipOnEmpty' => !empty($this->image)],
            [['title', 'body', 'url_alias', 'h1', 'meta_keywords', 'meta_description'], 'trim'],
            [['language_code', 'title'], 'required'],
            ['body', 'required', 'enableClientValidation' => false],
            [['title', 'url_alias', 'h1', 'meta_keywords', 'meta_description'], 'string', 'max' => 255],
            [['title', 'url_alias'], 'unique'],
            ['url_alias', 'required'],
            [['url_alias'], 'match', 'pattern' => '/^\w[\w\-\\s]*\w$/ui', 'enableClientValidation' => false],
            [['body'], 'string'],
            [['publication_date'], 'string'],
            [['publication_date'], 'match', 'pattern' => '/^\d{4}-\d{2}-\d{2}$/'],
            [['language_code'], 'string', 'max' => 16],
            [['postCategoryIds', 'targetAudienceIds', 'similarPostIds'], 'safe'],
            [['user_id'], 'integer'],
            ['tagValues', 'safe'],
            [['contains_images', 'contains_videos'], 'boolean'],
            ['status', 'default', 'value' => self::STATUS_REJECTED],
            ['status', 'in', 'range' => [self::STATUS_NEW, self::STATUS_ACCEPTED, self::STATUS_REJECTED]],
            [['rejection_reason'], 'trim', 'on' => self::SCENARIO_REJECTION],
            [['rejection_reason'], 'required', 'on' => self::SCENARIO_REJECTION],
        ];
    }

    public function transactions()
    {
        return [
            self::SCENARIO_DEFAULT => self::OP_ALL,
        ];
    }

    public static function find()
    {
        return new PostQuery(get_called_class());
    }

    /** @inheritdoc */
    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            if (!$this->publication_date) {
                $this->publication_date = date('Y-m-d', $this->created_at);
            }
            if ($this->url_alias) {
                $this->url_alias = $this->slugify($this->url_alias);
            }
            return true;
        }
        return false;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTags()
    {
        return $this->hasMany(Tag::className(), ['id' => 'tag_id'])
            ->viaTable('{{%post__tag}}', ['post_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPostCategories()
    {
        return $this->hasMany(PostCategory::className(), ['id' => 'post_category_id'])
            ->viaTable('{{%post__post_category}}', ['post_id' => 'id'])
            ->orderBy('{{%post_category}}.priority DESC');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTargetAudiences()
    {
        return $this->hasMany(TargetAudience::className(), ['id' => 'target_audience_id'])->viaTable('{{%post__target_audience}}', ['post_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPostSubject()
    {
        return $this->hasOne(PostSubject::className(), ['post_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getGalleryItems()
    {
        return $this->hasMany(GalleryItem::className(), ['post_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAuthor()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    /**
     * @param mixed $status
     * @return \yii\db\ActiveQuery
     */
    public function getSimilarPosts($status = null)
    {
        $status = $status ?: array_keys($this->getStatusList());
        return $this
            ->hasMany(Post::className(), ['id' => 'similar_post_id'])
            ->viaTable('{{%post__similar}}', ['post_id' => 'id'])
            ->where(['status' => $status]);
    }

    /**
     * @return bool
     */
    public function accept()
    {
        $this->status = self::STATUS_ACCEPTED;
        $updated = $this->save();
        if ($updated) {
            // trigger event
        }
        return $updated;
    }

    /**
     * @param string $reason
     * @return bool
     */
    public function reject($reason)
    {
        $this->status = self::STATUS_REJECTED;
        $this->rejection_reason = $reason;
        $updated = $this->save();
        if ($updated) {
            // trigger event
        }
        return $updated;
    }

    public static function getStatusList()
    {
        return [
            self::STATUS_NEW => Yii::t('app', 'Post Status New'),
            self::STATUS_ACCEPTED => Yii::t('app', 'Post Status Accepted'),
            self::STATUS_REJECTED => Yii::t('app', 'Post Status Rejected'),
        ];
    }
}
