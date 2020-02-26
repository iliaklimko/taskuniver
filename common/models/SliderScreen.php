<?php

namespace common\models;

use Yii;
use mongosoft\file\UploadImageBehavior;

/**
 * This is the model class for table "{{%slider_screen}}".
 *
 * @property integer $id
 * @property string $alias
 * @property string $image
 */
class SliderScreen extends \yii\db\ActiveRecord
{
    const ALIAS_01 = 'alias.01';
    const ALIAS_02 = 'alias.02';
    const ALIAS_03 = 'alias.03';
    const ALIAS_04 = 'alias.04';

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%slider_screen}}';
    }

    public function behaviors()
    {
        return [
            'uploadable' => [
                'class' => UploadImageBehavior::className(),
                'attribute' => 'image',
                'createThumbsOnSave' => false,
                'scenarios' => ['default'],
                'path' => '@webroot/uploads/slider-screen/{alias}',
                'url' => '@web/uploads/slider-screen/{alias}',
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['alias'], 'required'],
            [['alias'], 'string', 'max' => 255],
            [['image'], 'image', 'extensions' => 'jpg, jpeg, png, gif'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'alias' => 'Alias',
            'image' => 'Image',
        ];
    }
}
