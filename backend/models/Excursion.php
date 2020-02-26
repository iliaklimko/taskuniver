<?php

namespace backend\models;

use Yii;
use common\models\Excursion as CommonExcursion;
use common\models\Sight;
use common\models\City;

class Excursion extends CommonExcursion
{
    const PATTERN = '/^([\w\s]+)\s(\(([\w\s]+)\))$/ui';

    public $publication_date;

    public $new_sights;
    public $new_cities;

    public function rules()
    {
        return array_merge(
            parent::rules(),
            [
                [['new_sights', 'new_cities'], 'trim'],
                [
                    ['new_sights', 'new_cities'],
                    'match',
                    'pattern' => self::PATTERN,
                    'enableClientValidation' => false,
                ],
            ]
        );
    }

    protected function findSightByName($name)
    {
        return Sight::find()
            ->joinWith('translations')
            ->andWhere(['{{%sight_translation}}.name' => $name[0]])
            ->orWhere(['{{%sight_translation}}.name' => $name[1]])
            ->groupBy('{{%sight}}.id')
            ->one();
    }

    protected function findCityByName($name)
    {
        return City::find()
            ->joinWith('translations')
            ->andWhere(['{{%city_translation}}.name' => $name[0]])
            ->orWhere(['{{%city_translation}}.name' => $name[1]])
            ->groupBy('{{%city}}.id')
            ->one();
    }

    protected function findOrCreateSight($name)
    {
        if ($model = $this->findSightByName($name)) {
            return;
        }
        $model = new Sight();
        $model->translate('ru')->name = $name[0];
        $model->translate('en')->name = $name[1];
        $model->save();
        return $this->findSightByName($name);
    }

    protected function findOrCreateCity($name)
    {
        if ($model = $this->findCityByName($name)) {
            return;
        }
        $model = new City();
        $model->translate('ru')->name = $name[0];
        $model->translate('en')->name = $name[1];
        $model->save();
        return $this->findCityByName($name);
    }

    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);

        if ($this->new_sights) {
            preg_match(self::PATTERN, $this->new_sights, $matches);
            if ($newSight = $this->findOrCreateSight([$matches[1], $matches[3]])) {
                $this->link('sights', $newSight);
            }
        }

        if ($this->new_cities) {
            preg_match(self::PATTERN, $this->new_cities, $matches);
            if ($newCity = $this->findOrCreateCity([$matches[1], $matches[3]])) {
                $this->link('cities', $newCity);
            }
        }
    }
}
