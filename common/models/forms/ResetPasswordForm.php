<?php
namespace common\models\forms;

use Yii;
use yii\base\Model;
use yii\base\InvalidParamException;
use common\models\User;
use common\eventing\UserEvents;
use common\eventing\events\UserPasswordChangedEvent;

/**
 * Password reset form
 */
class ResetPasswordForm extends Model
{
    public $password;

    /**
     * @var \common\models\User
     */
    public $_user;

    public function attributeLabels()
    {
        return [
            'password' => Yii::t('app', 'Password'),
        ];
    }


    /**
     * Creates a form model given a token.
     *
     * @param string $token
     * @param array $config name-value pairs that will be used to initialize the object properties
     * @throws \yii\base\InvalidParamException if token is empty or not valid
     */
    public function __construct($token, $config = [])
    {
        if (empty($token) || !is_string($token)) {
            throw new InvalidParamException('Password reset token cannot be blank.');
        }
        $this->_user = User::findByPasswordResetToken($token);
        if (!$this->_user) {
            throw new InvalidParamException('Wrong password reset token.');
        }
        parent::__construct($config);
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['password', 'required'],
            ['password', 'string', 'min' => User::MIN_PASS_LEN],
        ];
    }

    /**
     * Resets password.
     *
     * @return boolean if password was reset.
     */
    public function resetPassword()
    {
        $user = $this->_user;
        $user->setPassword($this->password);
        $user->removePasswordResetToken();

        $saved = $user->save(false);
        if ($saved) {
            Yii::$app->trigger(
                UserEvents::PASSWORD_CHANGED,
                new UserPasswordChangedEvent([
                    'userId' => $user->id,
                ])
            );
        }
        return $saved;
    }
}