<?php
namespace common\models;

use Yii;
use yii\base\NotSupportedException;
use yii\behaviors\TimestampBehavior;
use mongosoft\file\UploadImageBehavior;
use yii2tech\ar\linkmany\LinkManyBehavior;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;

/**
 * User model
 *
 * @property integer $id
 * @property string $full_name
 * @property string $full_name_en
 * @property string $password_hash
 * @property string $password_reset_token
 * @property string $email
 * @property string $auth_key
 * @property integer $status
 * @property integer $pay_percent
 * @property integer $cash_payment
 * @property integer $created_at
 * @property integer $updated_at
 * @property string $number_card
 * @property string $bik
 * @property string $comment
 * @property integer $INN
 * @property integer $OGRN
 * @property string $password write-only password
 * @property string $phone
 * @property string $bio
 * @property string $avatar
 * @property string $signup_confirm_token
 * @property string $account_vkontakte
 * @property string $account_facebook
 * @property string $account_instagram
 * @property string $account_twitter
 * @property boolean $instant_confirmation
 * @property string $language_code
 * @property boolean $can_paid_by_card
 *
 * @property UserGroup $group
 * @property City[] $cities
 * @property Language[] $languages
 * @property Excursion[] $excursions
 */
class User extends ActiveRecord implements IdentityInterface
{
    const MIN_PASS_LEN = 8;

    const STATUS_INACTIVE = 0;
    const STATUS_ACTIVE = 10;


    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%user}}';
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            TimestampBehavior::className(),
            'uploadable' => [
                'class' => UploadImageBehavior::className(),
                'attribute' => 'avatar',
                'createThumbsOnSave' => false,
                'scenarios' => ['default'],
                'placeholder' => '@frontend/web/images/no-photo.gif',
                'path' => '@webroot/uploads/user/{id}',
                'url' => '@web/uploads/user/{id}',
            ],
            'linkCityBehavior' => [
                'class' => LinkManyBehavior::className(),
                'relation' => 'cities', // relation, which will be handled
                'relationReferenceAttribute' => 'cityIds', // virtual attribute, which is used for related records specification
            ],
            'linkLanguageBehavior' => [
                'class' => LinkManyBehavior::className(),
                'relation' => 'languages', // relation, which will be handled
                'relationReferenceAttribute' => 'languageIds', // virtual attribute, which is used for related records specification
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['email', 'full_name', 'full_name_en', 'bio', 'phone', 'account_vkontakte', 'account_facebook', 'account_instagram', 'account_twitter','INN','OGRN','comment','bik','number_card'], 'trim'],
            [['email'], 'required'],
            ['email', 'email'],
            ['phone', 'match', 'pattern' => '/^\+[-\d\s]*\d$/', 'message' => Yii::t('app', 'Phone is invalid')],
            [['full_name'], 'string', 'min' => 2, 'max' => 255],
            ['status', 'default', 'value' => self::STATUS_ACTIVE],
            ['status', 'in', 'range' => [self::STATUS_ACTIVE, self::STATUS_INACTIVE]],
            ['avatar', 'image', 'extensions' => 'jpg, jpeg, png, gif'],
            [['account_vkontakte', 'account_facebook', 'account_instagram', 'account_twitter'], 'url'],
            ['instant_confirmation', 'boolean'],
            [['interface_language'], 'required'],
            [['interface_language'], 'string', 'max' => 16],
            ['pay_percent', 'integer'],
            ['pay_cash', 'integer'],
            ['can_paid_by_card', 'boolean'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'user_group_id' => Yii::t('app', 'User group'),
            'cityIds' => Yii::t('app', 'Cities'),
            'languageIds' => Yii::t('app', 'Languages'),
        ];
    }

    /**
     * @inheritdoc
     */
    public static function findIdentity($id)
    {
        return static::findOne(['id' => $id, 'status' => self::STATUS_ACTIVE]);
    }

    /**
     * @inheritdoc
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        throw new NotSupportedException('"findIdentityByAccessToken" is not implemented.');
    }

    /**
     * Finds user by username
     *
     * @param string $username
     * @return static|null
     */
    public static function findByUsername($username)
    {
        return static::findOne(['email' => $username, /*'status' => self::STATUS_ACTIVE*/]);
    }

    /**
     * Finds user by password reset token
     *
     * @param string $token password reset token
     * @return static|null
     */
    public static function findByPasswordResetToken($token)
    {
        if (!static::isPasswordResetTokenValid($token)) {
            return null;
        }

        return static::findOne([
            'password_reset_token' => $token,
            'status' => self::STATUS_ACTIVE,
        ]);
    }

    /**
     * Finds out if password reset token is valid
     *
     * @param string $token password reset token
     * @return boolean
     */
    public static function isPasswordResetTokenValid($token)
    {
        if (empty($token)) {
            return false;
        }

        $timestamp = (int) substr($token, strrpos($token, '_') + 1);
        $expire = Yii::$app->params['user.passwordResetTokenExpire'];
        return $timestamp + $expire >= time();
    }

    /**
     * @inheritdoc
     */
    public function getId()
    {
        return $this->getPrimaryKey();
    }

    /**
     * @inheritdoc
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    /**
     * @inheritdoc
     */
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return boolean if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password, $this->password_hash);
    }

    /**
     * Generates password hash from password and sets it to the model
     *
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->password_hash = Yii::$app->security->generatePasswordHash($password);
    }

    /**
     * Generates "remember me" authentication key
     */
    public function generateAuthKey()
    {
        $this->auth_key = Yii::$app->security->generateRandomString();
    }

    /**
     * Generates new password reset token
     */
    public function generatePasswordResetToken()
    {
        $this->password_reset_token = Yii::$app->security->generateRandomString() . '_' . time();
    }

    /**
     * Removes password reset token
     */
    public function removePasswordResetToken()
    {
        $this->password_reset_token = null;
    }

    /**
     * Finds user by signup confirm token
     *
     * @param string $token signup confirm token
     * @return static|null
     */
    public static function findBySignupConfirmToken($token)
    {
        if (empty($token)) {
            return null;
        }

        return static::findOne([
            'signup_confirm_token' => $token,
        ]);
    }

    /**
     * Generates new signup confirm token
     */
    public function generateSignupConfirmToken()
    {
        $this->signup_confirm_token = Yii::$app->security->generateRandomString() . '_' . time();
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getGroup()
    {
        return $this->hasOne(UserGroup::className(), ['id' => 'user_group_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCities()
    {
        return $this->hasMany(City::className(), ['id' => 'city_id'])->viaTable('{{%user__city}}', ['user_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLanguages()
    {
        return $this->hasMany(Language::className(), ['id' => 'language_id'])->viaTable('{{%user__language}}', ['user_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getExcursions()
    {
        return $this->hasMany(Excursion::className(), ['user_id' => 'id']);
    }

    public function delete()
    {
        foreach ($this->excursions as $excursion) {
            $excursion->delete();
        }
        return parent::delete();
    }

    public function getPhoneNumber()
    {
        $phone = $this->phone;
        $pattern = '/[^\+\d]/';
        return preg_replace($pattern, '', $phone);
    }

    public function getFullName()
    {
        $fullname = $this->full_name;
        if (Yii::$app->language != 'ru' && !empty($this->full_name_en)) {
            $fullname = $this->full_name_en;
        }
        return $fullname;
    }
}
