<?php

namespace common\models;

use Yii;
use creocoder\translateable\TranslateableBehavior;
use common\models\translations\PremiumBlockTranslation;
use mongosoft\file\UploadImageBehavior;
use common\models\City;

/**
 * This is the model class for table "{{%premium_block}}".
 *
 * @property integer $id
 * @property string  $alias
 * @property string  $image
 * @property string  $url
 * @property string  $title
 * @property string  $subtitle
 * @property integer $city_id
 *
 * @property City $city
 */
class PremiumBlock extends \yii\db\ActiveRecord
{
    const ALIAS_BLOCK_1_1 = 'block_1.1';
    const ALIAS_BLOCK_1_2 = 'block_1.2';
    const ALIAS_BLOCK_1_3 = 'block_1.3';
    const ALIAS_BLOCK_1_4 = 'block_1.4';
    const ALIAS_BLOCK_2_1 = 'block_2.1';
    const ALIAS_BLOCK_2_2 = 'block_2.2';
    const ALIAS_BLOCK_2_3 = 'block_2.3';
    const ALIAS_BLOCK_2_4 = 'block_2.4';
    const ALIAS_BLOCK_2_5 = 'block_2.5';

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%premium_block}}';
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'translateable' => [
                'class' => TranslateableBehavior::className(),
                'translationAttributes' => ['title', 'subtitle'],
                'translationLanguageAttribute' => 'language_code',
            ],
            'uploadable' => [
                'class' => UploadImageBehavior::className(),
                'attribute' => 'image',
                'createThumbsOnSave' => false,
                'scenarios' => ['default'],
                'path' => '@webroot/uploads/premium-block/{id}',
                'url' => '@web/uploads/premium-block/{id}',
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['alias'], 'trim'],
            ['alias', 'required'],
            ['alias', 'unique'],
            ['city_id', 'required', 'on' => 'default'],
            ['city_id', 'integer'],
            ['image', 'string', 'on' => 'fake_scenario'],
            [['image'], 'image', 'on' => 'default', 'extensions' => 'jpg, jpeg, png, gif', 'skipOnEmpty' => !empty($this->image)],
            [['city_id'], 'integer'],
            [['city_id'], 'exist', 'skipOnError' => true, 'targetClass' => City::className(), 'targetAttribute' => ['city_id' => 'id']],
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
        return $this->hasMany(PremiumBlockTranslation::className(), ['premium_block_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCity()
    {
        return $this->hasOne(City::className(), ['id' => 'city_id']);
    }

    public function delete()
    {
        PremiumBlockTranslation::deleteAll(['premium_block_id' => $this->id]);
        return parent::delete();
    }
}
