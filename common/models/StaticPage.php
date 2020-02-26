<?php

namespace common\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use creocoder\translateable\TranslateableBehavior;
use Zelenin\yii\behaviors\Service\Slugifier;
use common\models\translations\StaticPageTranslation;

/**
 * This is the model class for table "{{%static_page}}".
 *
 * @property integer $id
 * @property string  $language_code
 * @property string  $url_alias
 * @property string  $title
 * @property string  $body
 * @property string  $h1
 * @property string  $meta_keywords
 * @property string  $meta_description
 * @property integer $created_at
 * @property integer $updated_at
 */
class StaticPage extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%static_page}}';
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            TimestampBehavior::className(),
            'translateable' => [
                'class' => TranslateableBehavior::className(),
                'translationAttributes' => ['title', 'body', 'h1', 'meta_keywords', 'meta_description'],
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
            [['url_alias'], 'trim'],
            [['url_alias'], 'string', 'max' => 255],
            [['url_alias'], 'unique'],
            [['url_alias'], 'match', 'pattern' => '/^\w[\w\-\\s]*\w$/ui', 'enableClientValidation' => false],
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
        return $this->hasMany(StaticPageTranslation::className(), ['static_page_id' => 'id']);
    }

    /** @inheritdoc */
    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            if (!empty($this->url_alias)) {
                $this->url_alias = $this->slugify($this->url_alias);
            } else {
                $id = $this->id;
                $this->url_alias = "page-{$id}";
            }
            return true;
        }
        return false;
    }

    protected function slugify($string)
    {
        $transliterateOptions = 'Russian-Latin/BGN; Any-Latin; Latin-ASCII; NFD; [:Nonspacing Mark:] Remove; NFC;';
        $replacement = '-';
        $lowercase = true;
        return (new Slugifier($transliterateOptions, $replacement, $lowercase))->slugify($string);
    }
}
