<?php

namespace common\models\forms;

use Yii;
use yii\base\Model;
use common\models\User;
use common\models\UserGroup;

class UserWithPlainPasswordForm extends Model
{
    /**
     * @var string
     */
    public $email;

    /**
     * @var string
     */
    public $password;

    /**
     * @var integer
     */
    public $group_id;

    /**
     * @var bool
     */
    public $console = false;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        $isNotConsole = function ($model) {
            return !$model->console;
        };

        return [
            [['email', 'password'], 'trim'],
            [['email', 'password'], 'required'],
            ['email', 'email'],
            ['email', 'unique', 'targetClass' => 'common\models\User'],
            ['password', 'string', 'min' => User::MIN_PASS_LEN, 'when' => $isNotConsole],
            ['group_id', 'integer'],
        ];
    }

    /**
     * Create user.
     *
     * @param  bool $console used in console controller
     * @return User|null the saved model or null if saving fails
     */
    public function create()
    {
        if ($this->validate()) {
            $user = new User([
                'email' => $this->email,
                'user_group_id' => $this->group_id,
            ]);
            $user->setPassword($this->password);
            if (!User::isPasswordResetTokenValid($user->password_reset_token)) {
                $user->generatePasswordResetToken();
            }
            if ($user->save(false)) {
                return $user;
            }
        }

        return null;
    }
}
