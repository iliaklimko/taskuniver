<?php

namespace common\models;

use Yii;
use creocoder\translateable\TranslateableBehavior;
use common\models\translations\CurrencyTranslation;

/**
 * This is the model class for table "{{%currency}}".
 *
 * @property integer $id
 * @property string  $name
 * @property string  $code
 * @property integer $amount_cnt
 * @property float   $amount
 * @property boolean $base
 */
class Currency extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%currency}}';
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
            [['code'], 'trim'],
            [['code'], 'required'],
            [['code'], 'string', 'max' => 20],
            [['code'], 'unique'],
            ['amount_cnt', 'integer'],
            ['amount', 'number'],
            ['base', 'boolean'],
            ['amount_cnt', 'default', 'value' => 1],
            ['amount', 'default', 'value' => 1.0],
            ['base', 'default', 'value' => false],
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
        return $this->hasMany(CurrencyTranslation::className(), ['currency_id' => 'id']);
    }

    public static function baseOptions()
    {
        return [
            0 => Yii::t('app', 'No'),
            1 => Yii::t('app', 'Yes'),
        ];
    }

    /**
     * @return bool
     */
    public function makeBase()
    {
        Currency::updateAll(['base' => false]);
        $this->base = true;
        $this->amount_cnt = 1;
        $this->amount = 1.0;
        $updated = $this->save(false);
        return $updated;
    }
}
