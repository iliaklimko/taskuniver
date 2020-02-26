<?php
namespace frontend\controllers;

use Yii;
use yii\base\InvalidParamException;
use yii\web\BadRequestHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\models\forms\LoginForm;
use common\models\forms\PasswordResetRequestForm;
use common\models\forms\ResetPasswordForm;
use frontend\models\SignupConfirmForm;
use frontend\models\SignupForm;
use frontend\models\ContactForm;
use common\models\City;
use common\models\Language;
use yii\widgets\ActiveForm;
use yii\web\Response;
use frontend\models\User;
use common\eventing\UserEvents;
use common\eventing\events\UserCreatedEvent;

/**
 * Site controller
 */
class SiteController extends BaseController
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout', 'signup'],
                'rules' => [
                    [
                        'actions' => ['signup'],
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                    'validate-login-form' => ['post'],
                    'validate-request-password-reset-form' => ['post'],
                ],
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    public function beforeAction($action)
    {
        if (!parent::beforeAction($action)) {
            return false;
        }
        if ($action->id == 'reset-password') {
            $this->layout = 'reset-password';
        }
        if ($action->id == 'signup') {
            $this->layout = 'signup';
        }
        return true;
    }

    /**
     * Displays homepage.
     *
     * @return mixed
     */
    public function actionIndex()
    {
        return $this->render('index');
    }

    /**
     * Logs in a user.
     *
     * @return mixed
     */
    public function actionLogin()
    {
        $returnTo = null;
        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            $returnTo = Yii::$app->request->post('returnTo');
            if ($returnTo) {
                return $this->redirect($returnTo);
            }

            $toOffice = ['user/edit-profile', 'locale' => $model->getUser()->interface_language != 'ru' ? $model->getUser()->interface_language : null];
            if (!Yii::$app->user->isGuest) {
                return $this->redirect($toOffice);
            }
            return $this->redirect($toOffice);
        } else {
            return $this->render('login', [
                'model' => $model,
            ]);
        }
    }

    public function actionValidateLoginForm()
    {
        $model = new LoginForm();
        $model->load(Yii::$app->request->post());
        if (Yii::$app->request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }
    }

    /**
     * Logs out the current user.
     *
     * @return mixed
     */
    public function actionLogout($returnTo = null)
    {
        Yii::$app->user->logout();
        if ($returnTo) {
            return $this->redirect($returnTo);
        }
        return $this->goHome();
    }


    /**
     * Signs user up.
     *
     * @return mixed
     */
    public function actionSignup()
    {
        $model = new SignupForm([
            'interface_language' => Yii::$app->language,
        ]);
        if ($model->load(Yii::$app->request->post())) {
            if ($user = $model->signup()) {
                return $this->redirect([
                    '/',
                    'locale' => $model->interface_language != 'ru' ? $model->interface_language : null,
                    '#' => 'registered'
                ]);
            }
        }

        return $this->render('signup', [
            'model' => $model,
            'cityList' => $this->getCityList(),
            'languageList' => $this->getLanguageList(),
        ]);
    }

    /**
     * Requests password reset.
     *
     * @return mixed
     */
    public function actionRequestPasswordReset()
    {
        $home = ['main-page/index', 'locale' => Yii::$app->request->get('locale') != 'ru' ? Yii::$app->request->get('locale') : null];
        $model = new PasswordResetRequestForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail()) {
                $home['#'] = 'success-msg';
                return $this->redirect($home);
            } else {
                $home['#'] = 'error-msg';
                return $this->redirect($home);
            }
        }

        return $this->render('requestPasswordResetToken', [
            'model' => $model,
        ]);
    }

    public function actionValidateRequestPasswordResetForm()
    {
        $model = new PasswordResetRequestForm();
        $model->load(Yii::$app->request->post());
        if (Yii::$app->request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }
    }

    /**
     * Resets password.
     *
     * @param string $token
     * @return mixed
     * @throws BadRequestHttpException
     */
    public function actionResetPassword($token)
    {
        try {
            $model = new ResetPasswordForm($token);
        } catch (InvalidParamException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }

        if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->resetPassword()) {
            return $this->redirect([
                'main-page/index',
                'locale' => Yii::$app->request->get('locale') != 'ru' ? Yii::$app->request->get('locale') : null,
                '#' => 'password-saved'
            ]);
        }

        return $this->render('resetPassword', [
            'model' => $model,
        ]);
    }

    /**
     * Confirm signup.
     *
     * @param string $token
     * @return mixed
     * @throws BadRequestHttpException
     */
    public function actionSignupConfirm($token)
    {
        try {
            $model = new SignupConfirmForm($token);
        } catch (InvalidParamException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }

        if ($model->setUserActive()) {
            return $this->redirect([
                'main-page/index',
                'locale' => $model->getUser()->interface_language != 'ru' ? $model->getUser()->interface_language : null,
                '#' => 'signup-confirmed',
            ]);
        }

        return $this->goHome();
    }

    public function actionPost($id)
    {
        $post = \common\models\Post::find()
            ->with(['galleryItems', 'postCategories', 'tags'])
            ->where(['post.id' => $id])
            ->one();
        $targetAudienceList = \common\models\TargetAudience::find()
            ->with('translations')
            ->all();
        $postCategoryList = \common\models\PostCategory::find()
            ->with('translations')
            ->all();
        $model = new \backend\models\Post();
        return $this->render('post', [
            'model' => $model,
            'post' => $post,
            'targetAudienceList' => $targetAudienceList,
            'postCategoryList' => $postCategoryList,
            'aboutList' => $this->getAboutList($model->subjectClass),
        ]);
    }

    protected function getAboutList($modelClass)
    {
        return $modelClass::find()->with('translations')->all();
    }

    public function actionAboutList($modelClass)
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        return \yii\helpers\ArrayHelper::map(
            $this->getAboutList($modelClass),
            'id',
            function ($model) {
                return $model->translate('en')->name;
            }
        );
    }

    protected function getCityList()
    {
        return City::find()
            ->joinWith('translations')
            ->with('translations')
            ->andWhere(['{{%city_translation}}.language_code' => Yii::$app->language])
            ->orderBy('{{%city_translation}}.name ASC')
            ->all();
    }

    protected function getLanguageList()
    {
        return Language::find()
            ->joinWith('translations')
            ->with('translations')
            ->andWhere(['{{%language_translation}}.language_code' => Yii::$app->language])
            ->orderBy('{{%language_translation}}.name ASC')
            ->all();
    }

    public function actionConfirmRetry($email)
    {
        $user = User::findOne(['email' => $email]);
        if ($user) {
            Yii::$app->trigger(
                UserEvents::USER_CREATED,
                new UserCreatedEvent([
                    'userId' => $user->id,
                ])
            );
            return $this->redirect(['main-page/index', '#' => 'registered']);
        }
        return $this->goHome();
    }

}
