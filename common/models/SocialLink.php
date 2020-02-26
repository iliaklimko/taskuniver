<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%social_link}}".
 *
 * @property integer $id
 * @property string $url
 * @property string $alias
 */
class SocialLink extends \yii\db\ActiveRecord
{
    const ALIAS_VKONTAKTE = 'vkontakte';
    const ALIAS_FACEBOOK = 'facebook';
    const ALIAS_INSTAGRAM = 'instagram';
    const ALIAS_TWITTER = 'twitter';

    const PREFIX_ICON = 'img/content';

    protected function iconList()
    {
        return [
            self::ALIAS_VKONTAKTE => 'vk.png',
            self::ALIAS_FACEBOOK => 'fb.png',
            self::ALIAS_INSTAGRAM => 'inst.png',
            self::ALIAS_TWITTER => 'tw.png',
        ];
    }

    public function getIconUrl()
    {
        return self::PREFIX_ICON.'/'.$this->iconList()[$this->alias];
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%social_link}}';
    }

    public function scenarios()
    {
        $scenarios = parent::scenarios();
        $scenarios['fake_scenario'] = ['alias'];
        return $scenarios;
    }
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['url'], 'required', 'on' => 'default'],
            [['alias'], 'trim'],
            [['alias'], 'required'],
            [['url', 'alias'], 'string', 'max' => 255],
            ['url', 'url'],
            [['url'], 'unique'],
            [['alias'], 'unique'],
        ];
    }
}
