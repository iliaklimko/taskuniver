<?php

namespace frontend\models;

use Yii;
use yii\helpers\Url;
use yii\helpers\Html;
use common\models\Excursion as CommonExcursion;
use frontend\models\interfaces\SearchResultItem;
use common\eventing\ExcursionEvents;
use common\eventing\events\ExcursionUpdatedByOwnerEvent;
use common\models\Sight;
use common\models\City;

class Excursion extends CommonExcursion implements SearchResultItem
{
    public $included_string;
    public $not_included_string;
    public $new_sights;
    public $new_cities;

    public function init()
    {
        parent::init();
        $this->current_price = 0;
        $this->old_price = 0;
    }

    public function rules()
    {
        return array_merge(
            parent::rules(),
            [
                [['new_sights', 'new_cities'], 'trim'],
                [['new_sights', 'new_cities'], 'match', 'pattern' => '/^[\w\s*]+(\s*,\s*[\w\s]+\s*)*$/ui'],
            ]
        );
    }

    protected function findSightByName($name)
    {
        return Sight::find()
            ->joinWith('translations')
            ->andWhere(['{{%sight_translation}}.name' => $name])
            ->groupBy('{{%sight}}.id')
            ->one();
    }

    protected function findCityByName($name)
    {
        return City::find()
            ->joinWith('translations')
            ->andWhere(['{{%city_translation}}.name' => $name])
            ->groupBy('{{%city}}.id')
            ->one();
    }

    protected function findOrCreateSight($name)
    {
        if ($model = $this->findSightByName($name)) {
            return;
        }
        $model = new Sight();
        $model->translate('ru')->name = $name;
        $model->translate('en')->name = $this->slugify($name);
        $model->save();
        return $this->findSightByName($name);
    }

    protected function findOrCreateCity($name)
    {
        if ($model = $this->findCityByName($name)) {
            return;
        }
        $model = new City();
        $model->translate('ru')->name = $name;
        $model->translate('en')->name = $this->slugify($name);
        $model->save();
        return $this->findCityByName($name);
    }

    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);

        if ($this->new_sights) {
            $sightString = $this->new_sights;
            $sightArray = explode(',', $sightString);
            foreach ($sightArray as $sight) {
                $sight = trim($sight);
                if ($newSight = $this->findOrCreateSight($sight)) {
                    $this->link('sights', $newSight);
                }
            }
        }

        if ($this->new_cities) {
            $cityString = $this->new_cities;
            $cityArray = explode(',', $cityString);
            foreach ($cityArray as $city) {
                $city = trim($city);
                if ($newCity = $this->findOrCreateCity($city)) {
                    $this->link('cities', $newCity);
                }
            }
        }

        if ($this->updated_by_owner) {
            Yii::$app->trigger(
                ExcursionEvents::EXCURSION_UPDATED_BY_OWNER,
                new ExcursionUpdatedByOwnerEvent([
                    'excursionId' => $this->id,
                ])
            );
        }
    }

    public function titleStr()
    {
        return $this->title;
    }

    public function imageUrl()
    {
        return $this->getUploadUrl('featured_image');
    }

    public function categoryArr()
    {
        $category = new \stdClass();
        $category->name = Yii::t('app', 'SearchPage.typeExcursion');
        return [
            $category,
        ];
    }

    public function audienceArr()
    {
        return $this->targetAudiences;
    }

    public function dateStr()
    {
        return Yii::$app->formatter->asDate($this->created_at, 'php:j F Y');
    }

    public function priceBlock()
    {
        if ($this->current_price * 100 > 0) {
            if ($this->old_price * 100 > 0) {
                $format = '<div class="old-price">%s<span class="%s">%s</span></div>%s<span class="%s">%s</span>';
                return sprintf(
                    $format,
                    Yii::$app->formatter->asDecimal(
                        Yii::$app->currencyConverter
                            ->setTo(Yii::$app->currency)->convert(
                                $this->currency->code,
                                $this->old_price
                            ),
                        2
                    ),
                    Yii::$app->currency == 'RUB' ? 'rouble' : 'euro',
                    Yii::$app->currency == 'RUB' ? 'a' : '&euro;',
                    Yii::$app->formatter->asDecimal(
                        Yii::$app->currencyConverter
                            ->setTo(Yii::$app->currency)->convert(
                                $this->currency->code,
                                $this->current_price
                            ),
                        2
                    ),
                    Yii::$app->currency == 'RUB' ? 'rouble' : 'euro',
                    Yii::$app->currency == 'RUB' ? 'a' : '&euro;'
                );
            } else {
                $format = '<div class="old-price"></div>%s<span class="%s">%s</span>';
                return sprintf(
                    $format,
                    Yii::$app->formatter->asDecimal(
                        Yii::$app->currencyConverter
                            ->setTo(Yii::$app->currency)->convert(
                                $this->currency->code,
                                $this->current_price
                            ),
                        2
                    ),
                    Yii::$app->currency == 'RUB' ? 'rouble' : 'euro',
                    Yii::$app->currency == 'RUB' ? 'a' : '&euro;'
                );
            }
        }

        return Yii::t('app', 'FreePrice') ;
    }

    public function durationStr()
    {
        return self::getDurationList()[$this->duration];
    }

    public function themeArray()
    {
        return $this->themes;
    }

    public function containsImages()
    {
        return false;
    }

    public function containsVideos()
    {
        return false;
    }

    public function guideName()
    {
        return $this->user->getFullName();
    }

    public function guideAvatar()
    {
        return $this->user->getUploadUrl('avatar');
    }

    public function startCityName()
    {
        $labels = [
            $this->startCity->name,
        ];
        if ($this->startCity->country) {
            $labels[] = $this->startCity->country->name;
        }
        return join(', ', $labels);
    }

    public function linkTo()
    {
        return Url::to([
            '/excursion/view',
            'excursion_id'    => $this->id,
            'target_audience' => null,
            'locale' => Yii::$app->language != 'ru' ? Yii::$app->language : null,
        ]);
    }

    public function idStr()
    {
        return $this->id;
    }

    public function editBlock()
    {
        if (Yii::$app->user->isGuest || (!Yii::$app->user->isGuest && Yii::$app->user->id != $this->user->id)) {
            return null;
        }

        $format = ''
            . '<div class="excursions__item-hover__links">'
            . '<a href="%s" class="edit"><img src="/static/excursion/dist/img/svg/edit.svg" alt=""></a>'
            . '%s'
            . '</div>'
        ;
        return sprintf(
                $format,
                Url::to([
                        '/user/update-excursion',
                        'id' => $this->id,
                        'locale' => $this->user->interface_language != 'ru' ? Yii::$app->language : null,
                        'lang' => $this->hasTranslation('ru') ? 'ru' : 'en',
                ]),
                Html::a(
                    '<img src="/static/excursion/dist/img/svg/delete.svg" alt="">',
                    ['/user/delete-excursion', 'id' => $this->id, 'locale' => $this->user->interface_language != 'ru' ? Yii::$app->language : null],
                    [
                        'data-method' => 'post',
                        'data-pjax' => '0',
                        'class' => 'delete',
                    ]
                )
        );


    }
}
