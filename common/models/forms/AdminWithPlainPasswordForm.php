<?php

namespace common\models\forms;

use Yii;
use yii\base\Model;
use common\models\Admin;
use common\models\AdminGroup;

class AdminWithPlainPasswordForm extends Model
{
    /**
     * @var string
     */
    public $email;

    /**
     * @var string
     */
    public $username;

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
            [['email', 'username', 'password'], 'trim'],
            [['email', 'username', 'password'], 'required'],
            ['username', 'match', 'pattern' => '/^[a-z]\w*$/i'],
            ['password', 'string'],
            ['password', 'string', 'min' => Admin::MIN_PASS_LEN, 'when' => $isNotConsole],
            ['email', 'email'],
            [['email', 'username'], 'unique', 'targetClass' => 'common\models\Admin'],
            ['group_id', 'integer'],
        ];
    }

    /**
     * Create admin.
     *
     * @param  bool $console used in console controller
     * @return Admin|null the saved model or null if saving fails
     */
    public function create()
    {
        if ($this->validate()) {
            $user = new Admin([
                'email' => $this->email,
                'username' => $this->username,
                'admin_group_id' => $this->group_id,
            ]);
            $user->setPassword($this->password);
            if (!Admin::isPasswordResetTokenValid($user->password_reset_token)) {
                $user->generatePasswordResetToken();
            }
            if ($user->save(false)) {
                return $user;
            }
        }

        return null;
    }
}
