<?php

namespace common\models;

use Yii;
use creocoder\translateable\TranslateableBehavior;
use common\models\translations\MainBenefitTranslation;
use mongosoft\file\UploadImageBehavior;

/**
 * This is the model class for table "{{%main_benefit}}".
 *
 * @property integer $id
 * @property string  $block
 * @property string  $icon
 * @property string  $title
 * @property string  $body
 */
class MainBenefit extends \yii\db\ActiveRecord
{
    const ALIAS_BLOCK_1_1 = 'block_1.1';
    const ALIAS_BLOCK_1_2 = 'block_1.2';
    const ALIAS_BLOCK_1_3 = 'block_1.3';
    const ALIAS_BLOCK_2_1 = 'block_2.1';
    const ALIAS_BLOCK_2_2 = 'block_2.2';
    const ALIAS_BLOCK_2_3 = 'block_2.3';

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%main_benefit}}';
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'translateable' => [
                'class' => TranslateableBehavior::className(),
                'translationAttributes' => ['title', 'body'],
                'translationLanguageAttribute' => 'language_code',
            ],
            'uploadable' => [
                'class' => UploadImageBehavior::className(),
                'attribute' => 'icon',
                'createThumbsOnSave' => false,
                'scenarios' => ['default'],
                'path' => '@webroot/uploads/main-benefit/{block}',
                'url' => '@web/uploads/main-benefit/{block}',
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['block'], 'trim'],
            ['block', 'required'],
            [['icon'], 'file', 'on' => 'default', 'extensions' => 'svg, jpg, jpeg, png, gif', 'checkExtensionByMimeType' => false],
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
        return $this->hasMany(MainBenefitTranslation::className(), ['main_benefit_id' => 'id']);
    }

    public function delete()
    {
        MainBenefitTranslation::deleteAll(['main_benefit_id' => $this->id]);
        return parent::delete();
    }
}
