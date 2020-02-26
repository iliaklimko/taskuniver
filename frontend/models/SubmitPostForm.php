<?php

namespace frontend\models;

use Yii;
use yii\base\Model;
use yii\web\UploadedFile;

class SubmitPostForm extends Model
{
    public $name;
    public $email;
    public $body;
    public $attachment;

    public function rules()
    {
        return [
            [['name', 'body', 'email'], 'trim'],
            [['name', 'body', 'email'], 'required'],
            [['name', 'body'], 'string', 'min' => 2],
            ['email', 'email'],
            ['attachment', 'file', 'maxSize' => 5*1024*1024, 'extensions' => 'pdf, doc, docx, xls, xlsx, png, jpg, jpeg, gif, txt, zip'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'name' => Yii::t('app', 'SubmitPostForm.name'),
            'body' => Yii::t('app', 'SubmitPostForm.body'),
            'email' => Yii::t('app', 'SubmitPostForm.email'),
            'attachment' => Yii::t('app', 'SubmitPostForm.attachment'),
        ];
    }

    public function submit()
    {
        if ($this->validate()) {
            $message = Yii::$app->mailer->compose()
                ->setTo([Yii::$app->params['editorEmail'] => Yii::$app->name])
                ->setFrom([$this->email => $this->name])
                ->setSubject('Globeall. You have received a post')
                ->setHtmlBody($this->body);
            if ($this->attachment) {
                $filename = Yii::getAlias('@webroot/uploads/post-attachments')
                    . '/'
                    . $this->attachment->baseName
                    . '.'
                    . $this->attachment->extension;
                $this->attachment->saveAs($filename);
                $message->attach($filename);
            }
            return $message->send();
        }
        return false;
    }
}
