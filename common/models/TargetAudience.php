<?php

namespace common\models;

use Yii;
use creocoder\translateable\TranslateableBehavior;
use common\models\translations\TargetAudienceTranslation;

/**
 * This is the model class for table "{{%target_audience}}".
 *
 * @property integer $id
 * @property string $image
 * @property string $name
 * @property integer $priority
 * @property string $alias
 * @property string $url_alias
 */
class TargetAudience extends \yii\db\ActiveRecord
{
    const ALIAS_HEAR = 'hear';
    const ALIAS_SEE = 'see';
    const ALIAS_WHEELCHAIR = 'wheelchair';
    const ALIAS_MOTHER = 'mother';
    const ALIAS_ALL = 'all';

    const PREFIX_ICON = 'img/content';

    protected function iconList()
    {
        return [
            self::ALIAS_HEAR => ['ear-sm.svg', 'head-icon2.png', 'head-icon2-h.png', 'page-header--index__icon--hearing', 'ear-red.svg', "news__icon news__icon--hearing"],
            self::ALIAS_SEE => ['eye-sm.svg', 'head-icon1.png', 'head-icon1-h.png', 'page-header--index__icon--eye', 'eye-red.svg', "news__icon news__icon--eye"],
            self::ALIAS_WHEELCHAIR => ['wheel-b-sm.svg', 'head-icon3.png', 'head-icon3-h.png', 'page-header--index__icon--disabled', 'wheel-red.svg', "news__icon news__icon--disable"],
            self::ALIAS_MOTHER => ['kids-sm.svg', 'head-icon4.png', 'head-icon4-h.png', 'page-header--index__icon--pram', 'kids-red.svg', "news__icon news__icon--pram"],
            self::ALIAS_ALL => ['top-icon-all.svg', null, null, null, null, null],
        ];
    }

    public function getTopIconUrl()
    {
        if ($this->alias == self::ALIAS_ALL) {
            return 'img/content'.'/'.$this->iconList()[$this->alias][0];
        }
        return 'img/svg'.'/'.$this->iconList()[$this->alias][0];
    }

    public function getHeaderIconUrl()
    {
        return self::PREFIX_ICON.'/'.$this->iconList()[$this->alias][1];
    }

    public function getHeaderIconHoverUrl()
    {
        return self::PREFIX_ICON.'/'.$this->iconList()[$this->alias][2];
    }

    public function getBgClass()
    {
        return $this->iconList()[$this->alias][3];
    }

    public function getPeopleCategoryUrl()
    {
        return 'img/svg'.'/'.$this->iconList()[$this->alias][4];
    }

    public function getAudienceClass()
    {
        return $this->iconList()[$this->alias][5];
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%target_audience}}';
    }

    public function scenarios()
    {
        $scenarios = parent::scenarios();
        $scenarios['fake_scenario'] = ['alias'];
        return $scenarios;
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
            ['url_alias', 'required',
                'on' => 'default',
                'when' => function ($model) {
                    return $model->alias != TargetAudience::ALIAS_ALL;
                },
                'enableClientValidation' => $this->alias != TargetAudience::ALIAS_ALL,
            ],
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
        return $this->hasMany(TargetAudienceTranslation::className(), ['target_audience_id' => 'id']);
    }
}
