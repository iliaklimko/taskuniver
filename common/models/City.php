<?php

namespace common\models;

use Yii;
use creocoder\translateable\TranslateableBehavior;
use common\models\translations\CityTranslation;
use mongosoft\file\UploadImageBehavior;

/**
 * This is the model class for table "{{%city}}".
 *
 * @property integer $id
 * @property string $image
 * @property string $name
 * @property integer $country_id
 *
 * @property Country $country
 * @property Excursion[] $excursions
 */
class City extends \yii\db\ActiveRecord
{
    public $excursion_count;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%city}}';
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
            'uploadable' => [
                'class' => UploadImageBehavior::className(),
                'attribute' => 'image',
                'createThumbsOnSave' => false,
                'scenarios' => ['default'],
                'path' => '@webroot/uploads/city/{id}',
                'url' => '@web/uploads/city/{id}',
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['image'], 'image', 'extensions' => 'jpg, jpeg, png, gif', 'skipOnEmpty' => true],
            [['country_id'], 'integer'],
            [['country_id'], 'exist', 'skipOnError' => true, 'targetClass' => Country::className(), 'targetAttribute' => ['country_id' => 'id']],
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
        return $this->hasMany(CityTranslation::className(), ['city_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCountry()
    {
        return $this->hasOne(Country::className(), ['id' => 'country_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getExcursions()
    {
        return $this->hasMany(Excursion::className(), ['start_city_id' => 'id']);
    }

    public function delete()
    {
        CityTranslation::deleteAll(['city_id' => $this->id]);
        return parent::delete();
    }
}
