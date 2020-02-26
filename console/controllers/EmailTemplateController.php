<?php

namespace console\controllers;

use Yii;
use yii\helpers\Console;
use yii\console\Controller;
use common\models\EmailTemplate;

class EmailTemplateController extends Controller
{
    public function actionInit()
    {
        $t1  = $this->createModel(EmailTemplate::ALIAS_USER_SIGNUP);
        $t2  = $this->createModel(EmailTemplate::ALIAS_USER_PASSWORD_RESET_REQUEST);
        $t3  = $this->createModel(EmailTemplate::ALIAS_USER_PASSWORD_CHANGE);
        $t4  = $this->createModel(EmailTemplate::ALIAS_EXCURSION_CREATED_BY_USER);
        $t5  = $this->createModel(EmailTemplate::ALIAS_EXCURSION_ACCEPTED);
        $t6  = $this->createModel(EmailTemplate::ALIAS_EXCURSION_REJECTED);
        $t7  = $this->createModel(EmailTemplate::ALIAS_EXCURSION_UPDATED_BY_OWNER);
        $t8  = $this->createModel(EmailTemplate::ALIAS_EXCURSION_ASSIGNED_TO_USER);
        $t9  = $this->createModel(EmailTemplate::ALIAS_ORDER_CHARGED_TO_USER);
        $t10 = $this->createModel(EmailTemplate::ALIAS_ORDER_CHARGED_TO_GUIDE);
        $t11 = $this->createModel(EmailTemplate::ALIAS_ORDER_GUIDE_ACCEPT);
        $t12 = $this->createModel(EmailTemplate::ALIAS_ORDER_GUIDE_REJECT);
        $t13 = $this->createModel(EmailTemplate::ALIAS_ORDER_CHARGED_TO_ADMIN);
        $t14 = $this->createModel(EmailTemplate::ALIAS_ORDER_GUIDE_ACCEPT_TO_USER);
        $t15 = $this->createModel(EmailTemplate::ALIAS_ORDER_GUIDE_REJECT_TO_USER);
        $t16 = $this->createModel(EmailTemplate::ALIAS_USER_PASSWORD_CHANGE_TO_HIMSELF);

        $t17 = $this->createModel(EmailTemplate::ALIAS_PAID_TO_GUIDE);

        if ($t1 && $t2 && $t3 && $t4 && $t5 && $t6 && $t7 && $t8 && $t9 && $t10 && $t11 && $t12 && $t13 && $t14 && $t15 && $t16 && $t17) {
            $this->stdout(sprintf("Default Email templates have been created.\n"), Console::FG_GREEN);

            return self::EXIT_CODE_NORMAL;
        }

        $this->stdout("Errors occurred.\n", Console::FG_RED);

        return self::EXIT_CODE_ERROR;
    }

    protected function createModel($alias)
    {
        $model = EmailTemplate::findOne(['alias' => $alias]);
        if (!$model) {
            $model = new EmailTemplate([
                'alias' => $alias,
            ]);
            return $model->save();
        }
        return true;
    }
}
