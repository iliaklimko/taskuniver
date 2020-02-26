<?php

namespace common\models;

use Yii;
use creocoder\translateable\TranslateableBehavior;
use common\models\translations\FooterMenuItemTranslation;

/**
 * This is the model class for table "{{%footer_menu_item}}".
 *
 * @property integer $id
 * @property string  $column
 * @property integer $position
 * @property string  $url
 * @property string  $title
 */
class FooterMenuItem extends \yii\db\ActiveRecord
{
    const COLUMN_01 = 'column.01';
    const COLUMN_02 = 'column.02';
    const COLUMN_03 = 'column.03';

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%footer_menu_item}}';
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'translateable' => [
                'class' => TranslateableBehavior::className(),
                'translationAttributes' => ['title', 'url'],
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
            ['column', 'trim'],
            ['column', 'required'],
            ['position', 'integer'],
            ['position', 'default', 'value' => 0],
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
        return $this->hasMany(FooterMenuItemTranslation::className(), ['footer_menu_item_id' => 'id']);
    }

    public function delete()
    {
        FooterMenuItemTranslation::deleteAll(['footer_menu_item_id' => $this->id]);
        return parent::delete();
    }

    public static function getColumnList()
    {
        return [
            self::COLUMN_01 => 'Column 01',
            self::COLUMN_02 => 'Column 02',
            self::COLUMN_03 => 'Column 03',
        ];
    }
}
