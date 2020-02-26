<?php

namespace frontend\controllers;

use Yii;
use yii\web\Controller;
use common\models\TargetAudience;
use common\models\PostCategory;
use common\models\SocialLink;
use common\models\StaticPage;
use common\models\forms\LoginForm;
use common\models\forms\PasswordResetRequestForm;
use common\models\Analytics;
use common\models\FooterMenuItem;

class BaseController extends Controller
{
    public function init()
    {
        parent::init();
        $this->setLanguage();
        $this->view->params['analytics'] = Analytics::findOne(['alias' => Analytics::ALIAS_BASE]);
        $this->view->params['targetAudienceList'] = $this->getTargetAudienceList();
        $this->view->params['postCategoryList'] = $this->getPostCategoryList();
        $this->view->params['socialLinkList'] = $this->getSocialLinkList();
        $this->view->params['footerMenu'] = $this->getFooterMenu();
        $this->view->params['loginForm'] = new LoginForm();
        $this->view->params['passwordResetRequestForm'] = new PasswordResetRequestForm();
    }

    protected function setLanguage()
    {
        Yii::$app->language = Yii::$app->request->get('locale', 'ru');
    }

    protected function getFooterMenu()
    {
        return FooterMenuItem::find()
            ->with('translations')
            ->orderBy('position DESC')
            ->all();
    }

    protected function getStaticPageList()
    {
        return StaticPage::find()
            ->with('translations')
            ->andWhere(['{{%static_page}}.id' => Yii::$app->params['footerLinks']])
            ->all();
    }

    protected function getTargetAudienceList()
    {
        return TargetAudience::find()
            ->with('translations')
            ->orderBy('priority DESC')
            ->indexBy('url_alias')
            ->all();
    }

    protected function getPostCategoryList()
    {
        return PostCategory::find()
            ->with('translations')
            ->orderBy('priority DESC')
            ->indexBy('url_alias')
            ->all();
    }

    protected function getSocialLinkList()
    {
        return SocialLink::find()
            ->indexBy('alias')
            ->all();
    }
}
