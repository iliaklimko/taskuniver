<?php

namespace frontend\models;

use Yii;
use yii\helpers\Url;
use common\models\Post as CommonPost;
use common\models\PostSubject;
use common\models\City;
use common\models\Sight;

class Post extends CommonPost
{
    public function titleStr()
    {
        return $this->title;
    }

    public function imageUrl()
    {
        return $this->getUploadUrl('image');
    }

    public function categoryArr()
    {
        return $this->postCategories;
    }

    public function audienceArr()
    {
        return $this->targetAudiences;
    }

    public function dateStr()
    {
        return Yii::$app->formatter->asDate($this->publication_date, 'php:j F Y');
    }

    public function priceBlock()
    {
        return false;
    }

    public function durationStr()
    {
        return false;
    }

    public function themeArray()
    {
        return false;
    }

    public function containsImages()
    {
        return $this->contains_images;
    }

    public function containsVideos()
    {
        return $this->contains_videos;
    }

    public function guideName()
    {
        return false;
    }

    public function guideAvatar()
    {
        return false;
    }

    public function startCityName()
    {
        $subject = PostSubject::findOne(['post_id' => $this->id]);

        if (!$subject) {
            return false;
        }

        if ($subject->model_class == PostSubject::MODEL_CLASS_CITY) {
            $city = City::findOne($subject->model_id);
            if ($city) {
                return $city->name;
            }
            return false;
        }

        if ($subject->model_class == PostSubject::MODEL_CLASS_SIGHT) {
            $sight = Sight::findOne($subject->model_id);
            if ($sight) {
                return $sight->city
                     ? ($sight->city->country ? $sight->city->name . ', ' . $sight->city->country->name : $sight->city->name)
                     : false;
            }
            return false;
        }

        return false;
    }

    public function linkTo()
    {
        return Url::to([
            '/post/view',
            'post_alias' => $this->url_alias,
            'target_audience' => null,
            'locale' => Yii::$app->language != 'ru' ? Yii::$app->language : null,
        ]);
    }

    public function idStr()
    {
        return null;
    }

    public function editBlock()
    {
        return null;
    }
}
