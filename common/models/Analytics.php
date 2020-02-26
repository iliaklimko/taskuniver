<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%analytics}}".
 *
 * @property integer $id
 * @property string $alias
 * @property string $body
 */
class Analytics extends \yii\db\ActiveRecord
{
    const ALIAS_BASE = 'alias.base';

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%analytics}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['alias'], 'required'],
            [['body'], 'string'],
            [['alias'], 'string', 'max' => 255],
        ];
    }
}
