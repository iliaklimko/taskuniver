<?php

namespace common\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use creocoder\translateable\TranslateableBehavior;
use common\models\translations\EmailTemplateTranslation;
use common\components\email\EmailTemplateInterface;

/**
 * This is the model class for table "{{%email_template}}".
 *
 * @property integer $id
 * @property string  $alias
 * @property string  $subject
 * @property string  $body
 * @property integer $created_at
 * @property integer $updated_at
 */
class EmailTemplate extends \yii\db\ActiveRecord implements EmailTemplateInterface
{
    const ALIAS_USER_SIGNUP                     = 'user.signup'; // гиду
    const ALIAS_USER_PASSWORD_RESET_REQUEST     = 'user.password.reset.request';
    const ALIAS_USER_PASSWORD_CHANGE            = 'user.password.change';
    const ALIAS_USER_PASSWORD_CHANGE_TO_HIMSELF = 'user.password.change.to.himself';

    const ALIAS_EXCURSION_CREATED_BY_USER   = 'excursion.created.by.user';
    const ALIAS_EXCURSION_ACCEPTED          = 'excursion.accepted';
    const ALIAS_EXCURSION_REJECTED          = 'excursion.rejected';
    const ALIAS_EXCURSION_UPDATED_BY_OWNER  = 'excursion.updated.by.owner';
    const ALIAS_EXCURSION_ASSIGNED_TO_USER  = 'excursion.assigned.to.user';

    const ALIAS_ORDER_CHARGED_TO_USER       = 'order.charged.to.user';
    const ALIAS_ORDER_CHARGED_TO_GUIDE      = 'order.charged.to.guide';
    const ALIAS_ORDER_GUIDE_ACCEPT          = 'order.guide.accept';
    const ALIAS_ORDER_GUIDE_REJECT          = 'order.guide.reject';
    const ALIAS_ORDER_CHARGED_TO_ADMIN      = 'order.charged.to.admin';
    const ALIAS_ORDER_GUIDE_ACCEPT_TO_USER  = 'order.guide.accept.to.user';
    const ALIAS_ORDER_GUIDE_REJECT_TO_USER  = 'order.guide.reject.to.user';

    const ALIAS_PAID_TO_GUIDE = 'order.paid.to.guide';

    private $_langCode = null;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%email_template}}';
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            TimestampBehavior::className(),
            'translateable' => [
                'class' => TranslateableBehavior::className(),
                'translationAttributes' => ['subject', 'body'],
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
            [['alias'], 'trim'],
            [['alias'], 'required'],
            [['alias'], 'string', 'max' => 255],
            [['alias'], 'unique'],
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
        return $this->hasMany(EmailTemplateTranslation::className(), ['email_template_id' => 'id']);
    }

    public function delete()
    {
        EmailTemplateTranslation::deleteAll(['email_template_id' => $this->id]);
        return parent::delete();
    }

    public function setLanguage($langCode)
    {
        $this->_langCode = $langCode;
    }

    public function getSubject()
    {
        $langCode = $this->_langCode;
        if ($langCode) {
            return $this->translate($langCode)->subject;
        }
        return $this->translate()->subject;
    }

    public function getBody()
    {
        $langCode = $this->_langCode;
        if ($langCode) {
            return $this->translate($langCode)->body;
        }
        return $this->translate()->body;
    }

    public static function getLegend()
    {
        return [
            self::ALIAS_USER_SIGNUP                 => [
                '{{full_name}}'    => 'Full name',
                '{{login}}'        => 'Login',
                '{{confirm_link}}' => 'Confirm link',
            ],
            self::ALIAS_USER_PASSWORD_RESET_REQUEST => [
                '{{full_name}}'    => 'Full name',
                '{{reset_link}}'   => 'Reset link',
            ],
            self::ALIAS_USER_PASSWORD_CHANGE        => [
                '{{full_name}}'    => 'Full name',
                '{{profile_link}}' => 'Guide profile link',
            ],
            self::ALIAS_USER_PASSWORD_CHANGE_TO_HIMSELF  => [
                '{{full_name}}'    => 'Full name',
                '{{reset_link}}'   => 'Reset link',
            ],
            self::ALIAS_EXCURSION_CREATED_BY_USER   => [
                '{{excursion_id}}'     => 'Excursion ID',
                '{{excursion_link}}'   => 'Link to excursion page',
            ],
            self::ALIAS_EXCURSION_ACCEPTED          => [
                '{{full_name}}'        => 'Full name',
                '{{excursion_id}}'     => 'Excursion ID',
                '{{excursion_link}}'   => 'Link to excursion page',
            ],
            self::ALIAS_EXCURSION_REJECTED          => [
                '{{full_name}}'        => 'Full name',
                '{{excursion_id}}'     => 'Excursion ID',
                '{{rejection_reason}}' => 'Rejection reason',
            ],
            self::ALIAS_EXCURSION_UPDATED_BY_OWNER  => [
                '{{excursion_id}}'     => 'Excursion ID',
                '{{excursion_link}}'   => 'Link to excursion page',
                '{{profile_link}}'     => 'Guide profile link',
            ],
            self::ALIAS_EXCURSION_ASSIGNED_TO_USER  => [
                '{{full_name}}'        => 'Full name',
            ],
            self::ALIAS_ORDER_CHARGED_TO_USER  => [
                '{{order_id}}'                  => 'Order ID',
                '{{excursion_title}}'           => 'Excursion title',
                '{{order_person_number}}'       => 'Order person number',
                '{{order_date}}'                => 'Order date',
                '{{order_price}}'               => 'Order price',
            ],
            self::ALIAS_ORDER_CHARGED_TO_GUIDE => [
                '{{order_id}}'                  => 'Order ID',
                '{{excursion_title}}'           => 'Excursion title',
                '{{order_person_number}}'       => 'Order person number',
                '{{order_date}}'                => 'Order date',
                '{{customer_name}}'             => 'Customer name',
                '{{order_email}}'               => 'Customer email',
                '{{order_phone}}'               => 'Customer phone',
                '{{order_link}}'                => 'Order link',
                '{{order_price}}'               => 'Order price',
            ],
            self::ALIAS_ORDER_GUIDE_ACCEPT => [
                '{{full_name}}'        => 'Full name',
                '{{excursion_id}}'     => 'Excursion ID',
                '{{details}}'          => 'Details',
                '{{profile_link}}'     => 'Guide profile link',
                '{{orders_link}}'      => 'Orders link',
            ],
            self::ALIAS_ORDER_GUIDE_REJECT => [
                '{{full_name}}'        => 'Full name',
                '{{excursion_id}}'     => 'Excursion ID',
                '{{details}}'          => 'Details',
                '{{profile_link}}'     => 'Guide profile link',
                '{{orders_link}}'      => 'Orders link',
            ],
            self::ALIAS_ORDER_CHARGED_TO_ADMIN => [
                '{{excursion_title}}' => 'Excursion title',
                '{{profile_link}}'    => 'Profile link',
                '{{excursion_link}}'  => 'Excursion link',
            ],
            self::ALIAS_ORDER_GUIDE_ACCEPT_TO_USER => [
                '{{profile_link}}' => 'Profile link',
                '{{orders_link}}'  => 'Orders link',
                '{{details}}'      => 'Details',
                '{{order_id}}'     => 'Order ID',
            ],
            self::ALIAS_ORDER_GUIDE_REJECT_TO_USER => [
                '{{profile_link}}'        => 'Profile link',
                '{{orders_link}}'         => 'Orders link',
                '{{details}}'             => 'Details',
                '{{order_id}}'            => 'Order ID',
                '{{excursion_title}}'     => 'Excursion title',
                '{{order_person_number}}' => 'Order person number',
                '{{order_date}}'          => 'Order date',
                '{{order_price}}'         => 'Order price',
                '{{support_email}}'       => 'Support email',
            ],

            self::ALIAS_PAID_TO_GUIDE => [
                '{{order_id}}'                  => 'Order ID',
                '{{excursion_id}}'              => 'Excursion ID',
                '{{excursion_title}}'           => 'Excursion title',
                '{{order_price}}'               => 'Order price',
                '{{order_person_number}}'       => 'Order person number',
                '{{order_date}}'                => 'Order date',
            ],
        ];
    }
}
