<?php

namespace common\models;

use Yii;
use creocoder\translateable\TranslateableBehavior;
use common\models\translations\ExcursionTypeTranslation;

/**
 * This is the model class for table "{{%excursion_type}}".
 *
 * @property integer $id
 * @property integer $priority
 *
 * @property Excursion[] $excursions
 */
class ExcursionType extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%excursion_type}}';
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
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getExcursions()
    {
        return $this->hasMany(Excursion::className(), ['id' => 'excursion_id'])->viaTable('{{%excursion__excursion_type}}', ['excursion_type_id' => 'id']);
    }

    public function transactions()
    {
        return [
            self::SCENARIO_DEFAULT => self::OP_INSERT | self::OP_UPDATE,
        ];
    }

    public function getTranslations()
    {
        return $this->hasMany(ExcursionTypeTranslation::className(), ['excursion_type_id' => 'id']);
    }

    public function delete()
    {
        ExcursionTypeTranslation::deleteAll(['excursion_type_id' => $this->id]);
        return parent::delete();
    }
}
