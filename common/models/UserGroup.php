<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%user_group}}".
 *
 * @property integer $id
 * @property string $name
 * @property string $alias
 *
 * @property User[] $users
 */
class UserGroup extends \yii\db\ActiveRecord
{
    const ALIAS_REGISTERED = 'registered';
    const ALIAS_GUIDE = 'guide';

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%user_group}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'], 'trim'],
            [['name'], 'required'],
            [['name'], 'string', 'max' => 255],
            [['name'], 'unique'],
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUsers()
    {
        return $this->hasMany(User::className(), ['user_group_id' => 'id']);
    }
}
