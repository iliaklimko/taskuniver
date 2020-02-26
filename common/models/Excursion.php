<?php

namespace common\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use common\components\behaviors\TaggableBehavior;
use yii2tech\ar\linkmany\LinkManyBehavior;
use creocoder\translateable\TranslateableBehavior;
use common\models\translations\ExcursionTranslation;
use mongosoft\file\UploadImageBehavior;
use Zelenin\yii\behaviors\Service\Slugifier;
use common\eventing\ExcursionEvents;
use common\eventing\events\ExcursionRejectedEvent;
use common\eventing\events\ExcursionAcceptedEvent;
use common\models\query\ExcursionQuery;

/**
 * This is the model class for table "{{%excursion}}".
 *
 * @property integer $id
 * @property integer $user_id
 * @property integer $start_city_id
 * @property string $current_price
 * @property string $free_cancellation
 * @property string $old_price
 * @property integer $duration
 * @property integer $person_number
 * @property integer $pick_up_from_hotel
 * @property integer $one_time_excursion
 * @property integer $visitors
 * @property integer $date_array
 * @property integer $set_to
 * @property integer $time_spending
 * @property integer $created_at
 * @property integer $updated_at
 * @property string $featured_image
 * @property integer $status
 * @property string $rejection_reason
 * @property string $dates
 * @property string $start_time
 * @property boolean $updated_by_owner
 * @property int $group_id
 *
 * @property City $startCity
 * @property User $user
 * @property City[] $cities
 * @property ExcursionTheme[] $themes
 * @property ExcursionType[] $types
 * @property Language[] $languages
 * @property Sight[] $sights
 * @property TargetAudience[] $targetAudiences
 * @property ExcursionImage[] $images
 * @property Excursion[] $similarExcursions
 * @property Currency $currency
 */
class Excursion extends \yii\db\ActiveRecord
{
    const STATUS_NEW = 0;
    const STATUS_ACCEPTED = 10;
    const STATUS_REJECTED = 20;

    const SCENARIO_REJECTION = 'rejection';
    const DURATION_3 = 10;
    const DURATION_6 = 20;
    const DURATION_9 = 30;
    const DURATION_24 = 80;
    const DURATION_MANY = 90;

    const NUMBER_5 = 5;
    const NUMBER_10 = 10;
    const NUMBER_20 = 20;

    public $excursion_price_status;

    public $publication_date;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%excursion}}';
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            TimestampBehavior::className(),
            'taggable' => [
                'class' => TaggableBehavior::className(),
                'tagValuesAsArray' => true,
            ],
            'translateable' => [
                'class' => TranslateableBehavior::className(),
                'translationAttributes' => [
                    'title',
                    'description',
                    'included_in_price',
                    'not_included_in_price',
                    'meeting_point',
                    'additional_info',
                ],
                'translationLanguageAttribute' => 'language_code',
            ],
            'linkTargetAudienceBehavior' => [
                'class' => LinkManyBehavior::className(),
                'relation' => 'targetAudiences', // relation, which will be handled
                'relationReferenceAttribute' => 'targetAudienceIds', // virtual attribute, which is used for related records specification
            ],
            'linkLanguageBehavior' => [
                'class' => LinkManyBehavior::className(),
                'relation' => 'languages', // relation, which will be handled
                'relationReferenceAttribute' => 'languageIds', // virtual attribute, which is used for related records specification
            ],
            'linkThemeBehavior' => [
                'class' => LinkManyBehavior::className(),
                'relation' => 'themes', // relation, which will be handled
                'relationReferenceAttribute' => 'themeIds', // virtual attribute, which is used for related records specification
            ],
            'linkTypeBehavior' => [
                'class' => LinkManyBehavior::className(),
                'relation' => 'types', // relation, which will be handled
                'relationReferenceAttribute' => 'typeIds', // virtual attribute, which is used for related records specification
            ],
            'linkCityBehavior' => [
                'class' => LinkManyBehavior::className(),
                'relation' => 'cities', // relation, which will be handled
                'relationReferenceAttribute' => 'cityIds', // virtual attribute, which is used for related records specification
            ],
            'linkSightBehavior' => [
                'class' => LinkManyBehavior::className(),
                'relation' => 'sights', // relation, which will be handled
                'relationReferenceAttribute' => 'sightIds', // virtual attribute, which is used for related records specification
            ],
            'linkSimilarExcursionBehavior' => [
                'class' => LinkManyBehavior::className(),
                'relation' => 'similarExcursions', // relation, which will be handled
                'relationReferenceAttribute' => 'similarExcursionIds', // virtual attribute, which is used for related records specification
            ],
            'uploadable' => [
                'class' => UploadImageBehavior::className(),
                'attribute' => 'featured_image',
                'createThumbsOnSave' => false,
                'scenarios' => ['default'],
                'path' => '@webroot/uploads/excursion/{id}/featured-image',
                'url' => '@web/uploads/excursion/{id}/featured-image',
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['updated_by_owner', 'boolean'],
            [['dates'], 'trim'],
            [['set_to'], 'trim'],
            [['date_array'], 'trim'],
            [['one_time_excursion'], 'trim'],
            [['time_spending'], 'trim'],
            ['dates', 'match', 'pattern' => '/^\d{4}\-\d{2}\-\d{2}\s\d{2}:\d{2}\s*(\s*,\s*\d{4}\-\d{2}\-\d{2}\s\d{2}:\d{2})*$/'],
            ['time_spending', 'match', 'pattern' =>'(\d{1,2}:\d{2})'],
            [['user_id', 'targetAudienceIds', 'languageIds', 'themeIds', 'typeIds', 'group_id','time_spending','date_array'], 'required'],
            [['sightIds', 'cityIds', 'similarExcursionIds'], 'safe'],
            [['targetAudienceIds', 'start_city_id', 'languageIds', 'duration', 'themeIds', 'typeIds', 'person_number', 'currency_id'], 'required'],
            [['user_id', 'start_city_id', 'duration', 'person_number', 'currency_id'], 'integer'],
            [['pick_up_from_hotel'], 'boolean'],
            [['current_price', 'old_price','free_cancellation'], 'default', 'value' => 0],
            [['current_price', 'old_price','free_cancellation','visitors'], 'number'],
            [['currency_id'], 'exist', 'skipOnError' => true, 'targetClass' => Currency::className(), 'targetAttribute' => ['currency_id' => 'id']],
            [['start_city_id'], 'exist', 'skipOnError' => true, 'targetClass' => City::className(), 'targetAttribute' => ['start_city_id' => 'id']],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
            [['featured_image'], 'image', 'extensions' => 'jpg, jpeg, png, gif', 'skipOnEmpty' => !empty($this->featured_image)],
            ['status', 'default', 'value' => self::STATUS_NEW],
            ['status', 'in', 'range' => [self::STATUS_NEW, self::STATUS_ACCEPTED, self::STATUS_REJECTED]],
            [['rejection_reason'], 'trim', 'on' => self::SCENARIO_REJECTION],
            [['rejection_reason'], 'required', 'on' => self::SCENARIO_REJECTION],
            ['tagValues', 'safe'],
        ];
    }

    public static function find()
    {
        return new ExcursionQuery(get_called_class());
    }

    // /** @inheritdoc */
    // public function beforeValidate()
    // {
    //     if (parent::beforeValidate()) {
    //         if (empty($this->translate('en')->title)
    //             && empty($this->translate('en')->description)
    //             && empty($this->translate('en')->included_in_price)
    //             && empty($this->translate('en')->not_included_in_price)
    //             && empty($this->translate('en')->meeting_point)
    //             && empty($this->translate('en')->additional_info)
    //         ) {
    //             $this->translate('en')->title                 = $this->translate('ru')->title;                 //$this->slugify($this->translate('ru')->title);
    //             $this->translate('en')->description           = $this->translate('ru')->description;           //$this->slugify($this->translate('ru')->description);
    //             $this->translate('en')->included_in_price     = $this->translate('ru')->included_in_price;     //$this->slugify($this->translate('ru')->included_in_price);
    //             $this->translate('en')->not_included_in_price = $this->translate('ru')->not_included_in_price; //$this->slugify($this->translate('ru')->not_included_in_price);
    //             $this->translate('en')->meeting_point         = $this->translate('ru')->meeting_point;         //$this->slugify($this->translate('ru')->meeting_point);
    //             $this->translate('en')->additional_info       = $this->translate('ru')->additional_info;       //$this->slugify($this->translate('ru')->additional_info);
    //         }
    //         return true;
    //     }
    //     return false;
    // }

    public function delete()
    {
        ExcursionTranslation::deleteAll(['excursion_id' => $this->id]);
        return parent::delete();
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTags()
    {
        return $this->hasMany(Tag::className(), ['id' => 'tag_id'])
            ->viaTable('{{%excursion__tag}}', ['excursion_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCurrency()
    {
        return $this->hasOne(Currency::className(), ['id' => 'currency_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStartCity()
    {
        return $this->hasOne(City::className(), ['id' => 'start_city_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCities()
    {
        return $this->hasMany(City::className(), ['id' => 'city_id'])->viaTable('{{%excursion__city}}', ['excursion_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getThemes()
    {
        return $this->hasMany(ExcursionTheme::className(), ['id' => 'excursion_theme_id'])->viaTable('{{%excursion__excursion_theme}}', ['excursion_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTypes()
    {
        return $this->hasMany(ExcursionType::className(), ['id' => 'excursion_type_id'])->viaTable('{{%excursion__excursion_type}}', ['excursion_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLanguages()
    {
        return $this->hasMany(Language::className(), ['id' => 'language_id'])->viaTable('{{%excursion__language}}', ['excursion_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSights()
    {
        return $this->hasMany(Sight::className(), ['id' => 'sight_id'])->viaTable('{{%excursion__sight}}', ['excursion_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTargetAudiences()
    {
        return $this->hasMany(TargetAudience::className(), ['id' => 'target_audience_id'])->viaTable('{{%excursion__target_audience}}', ['excursion_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getImages()
    {
        return $this->hasMany(ExcursionImage::className(), ['excursion_id' => 'id']);
    }

    public function transactions()
    {
        return [
            self::SCENARIO_DEFAULT => self::OP_INSERT | self::OP_UPDATE,
        ];
    }

    public function getTranslations()
    {
        return $this->hasMany(ExcursionTranslation::className(), ['excursion_id' => 'id']);
    }

    public static function getDurationList()
    {
        return [
            self::DURATION_3    => Yii::t('app', 'Up to 3 h'),
            self::DURATION_6    => Yii::t('app', '3-6 h'),
            self::DURATION_9    => Yii::t('app', '6-9 h'),
            self::DURATION_24   => Yii::t('app', 'Whole day'),
            self::DURATION_MANY => Yii::t('app', 'Many days'),
        ];
    }

    public static function getPersonNumberList()
    {
        return [
            self::NUMBER_5  => Yii::t('app', 'Up to 5 persons'),
            self::NUMBER_10 => Yii::t('app', '5-10 persons'),
            self::NUMBER_20 => Yii::t('app', '10-20 persons'),
        ];
    }

    protected function slugify($string)
    {
        $transliterateOptions = 'Russian-Latin/BGN; Any-Latin; Latin-ASCII; NFD; [:Nonspacing Mark:] Remove; NFC;';
        $replacement = ' ';
        $lowercase = false;
        return (new Slugifier($transliterateOptions, $replacement, $lowercase))->slugify($string);
    }

    public static function priceStatus()
    {
        return [
            0 => Yii::t('app', 'Free'),
            1 => Yii::t('app', 'Pay')
        ];
    }

    /**
     * @return bool
     */
    public function accept()
    {
        $this->status = self::STATUS_ACCEPTED;
        $this->rejection_reason = null;
        $updated = $this->save(false);
        if ($updated) {
            Yii::$app->trigger(
                ExcursionEvents::EXCURSION_ACCEPTED,
                new ExcursionAcceptedEvent([
                    'excursionId' => $this->id,
                ])
            );
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
        $this->updated_by_owner = false;
        $updated = $this->save(false);
        if ($updated) {
            Yii::$app->trigger(
                ExcursionEvents::EXCURSION_REJECTED,
                new ExcursionRejectedEvent([
                    'excursionId' => $this->id,
                ])
            );
        }
        return $updated;
    }

    public static function getStatusList()
    {
        return [
            self::STATUS_NEW => Yii::t('app', 'New'),
            self::STATUS_ACCEPTED => Yii::t('app', 'Accepted'),
            self::STATUS_REJECTED => Yii::t('app', 'Rejected'),
        ];
    }

    public function getNearestDate()
    {
        $values = $this->getNearestDates();
        return !empty($values)
            ? $this->getNearestDates()[0]
            : null;
    }

    public function getNearestTime()
    {
        if ($this->getNearestDate()) {
            $nearestDate = trim($this->getNearestDate());
            $nearestTime = substr($nearestDate, -5);
            return $nearestTime;
        }
        return null;
    }

    public function getNearestDates()
    {
        if (empty($this->dates)) {
            return [];
        }

        $dtNow = new \DateTime('-1 day');
        $dateArray = explode(',', $this->dates);
        $dates = [];
        foreach ($dateArray as $dateString) {
            $dateString = trim($dateString);
            $dateAndTime = preg_split('/\s/', $dateString);
            $ymd = explode('-', $dateAndTime[0]);
            $hm = isset($dateAndTime[1])
                ? explode(':', $dateAndTime[1])
                : ['00', '00'];
            $dt = new \DateTime();
            $dt->setDate($ymd[0], $ymd[1], $ymd[2]);
            $dt->setTime($hm[0], $hm[1]);
            if ($dt->getTimestamp() > $dtNow->getTimestamp()) {
                $dates[] = $dateString;
            }
        }
        return $dates;
    }

    /**
     * @param mixed $status
     * @return \yii\db\ActiveQuery
     */
    public function getSimilarExcursions($status = null)
    {
        $status = $status ?: array_keys($this->getStatusList());
        return $this
            ->hasMany(static::className(), ['id' => 'similar_excursion_id'])
            ->viaTable('{{%excursion__similar}}', ['excursion_id' => 'id'])
            ->where(['status' => $status]);
    }

    public function getExcursion_groups() {
        return $this->hasOne(ExcursionGroups::className(), ['id' => 'group_id']);
    }

    public function getDatesAndQuantity($id)
    {

        $model = Order::find()->where('excursion_id = :id', [':id' => $id])->all();
        $arrDateAndQuantity = array();

        if ($model) {
            foreach ($model as $m) {

                $newArr[$m->date][] = $m->quantity;
            }
            $arrDateAndQuantity = $newArr;
        }
        return $arrDateAndQuantity;
    }

    public function getCountExcursion($id)
    {
        $model = Excursion::find()->where(['id' => $id])->one();

        if ($model) {
            return $model;
        }
    }

    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            $arrPostExcursion = Yii::$app->request->post('Excursion');
            $oneTimeExcursion = $arrPostExcursion['one_time_excursion'];

            $arrDaysAndCount = array(
                'monday' => array('active' => $arrPostExcursion['mondayDays'], 'count' => $arrPostExcursion['monday']),
                'tuesday' => array('active' => $arrPostExcursion['tuesdayDays'], 'count' => $arrPostExcursion['tuesday']),
                'wednesday' => array('active' => $arrPostExcursion['wednesdayDays'], 'count' => $arrPostExcursion['wednesday']),
                'thursday' => array('active' => $arrPostExcursion['thursdayDays'], 'count' => $arrPostExcursion['thursday']),
                'friday' => array('active' => $arrPostExcursion['fridayDays'], 'count' => $arrPostExcursion['friday']),
                'saturday' => array('active' => $arrPostExcursion['saturdayDays'], 'count' => $arrPostExcursion['saturday']),
                'sunday' => array('active' => $arrPostExcursion['sundayDays'], 'count' => $arrPostExcursion['sunday']),
            );
            $this->date_array = serialize($arrDaysAndCount);

            if ($oneTimeExcursion == 'Y') {
                $countPlacesOneTimeExcursion = $arrPostExcursion['visitors'];
                $date = $arrPostExcursion['set_to'];
                $this->visitors = $countPlacesOneTimeExcursion;
                $this->set_to = $date;
                return true;
            } else {

                if (empty($arrPostExcursion['set_to'])) {
                    $date = date('Y-m-d', strtotime('+1 years'));
                    $this->set_to = $date;
                }

                return true;
            }

        } else {
            return false;
        }
    }
}
