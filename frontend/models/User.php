<?php

namespace frontend\models;

use Yii;
use common\models\User as CommonUser;

class User extends CommonUser
{
    public function rules()
    {
        return array_merge(
            parent::rules(),
            [
                [['user_group_id', 'full_name', 'phone', 'bio'], 'required'],
                [['cityIds', 'languageIds'], 'required'],
            ]
        );
    }

    public function attributeLabels()
    {
        return [
            'user_group_id'     => Yii::t('app', 'User group'),
            'cityIds'           => Yii::t('app', 'User.cities'),
            'languageIds'       => Yii::t('app', 'User.languages'),
            'account_vkontakte' => Yii::t('app', 'User.vkLink'),
            'account_facebook'  => Yii::t('app', 'User.fbLink'),
            'account_instagram' => Yii::t('app', 'User.instLink'),
            'account_twitter'   => Yii::t('app', 'User.twiLink'),
            'full_name'         => Yii::t('app', 'User.fullname'),
            'full_name_en'      => Yii::t('app', 'User.fullname_en'),
            'email'             => Yii::t('app', 'User.email'),
            'phone'             => Yii::t('app', 'User.phone'),
            'bio'               => Yii::t('app', 'User.bio'),
            'avatar'            => Yii::t('app', 'User.avatar'),
        ];
    }
}
