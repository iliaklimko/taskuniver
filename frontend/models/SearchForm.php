<?php

namespace frontend\models;

use Yii;
use yii\base\Model;
use common\models\PostSubject;
use common\models\PostCategory;
use frontend\models\Post;
use frontend\models\Excursion;
use yii\data\Pagination;

class SearchForm extends Model
{
    public $q;
    public $target_audience;

    public function rules()
    {
        return [
            ['q', 'trim'],
            ['q', 'safe'],
        ];
    }

    public function formName()
    {
        return '';
    }

    public function searchExcursions($target_audience = null)
    {
        $q = $this->q;

        if (empty($q)) {
            return [
                'count'      => 0,
                'list'       => [],
            ];
        }

        $query = Excursion::find()
            ->select([
                '{{%excursion}}.*',
                'FROM_UNIXTIME({{%excursion}}.created_at, "%Y-%m-%d") AS publication_date'
            ])
            ->joinWith([
                'translations',
                'startCity',
                'startCity.translations' => function ($query) {
                    $query->onCondition(['{{%city_translation}}.language_code' => Yii::$app->language]);
                },
                'sights',
                'sights.translations' => function ($query) {
                    $query->onCondition(['{{%sight_translation}}.language_code' => Yii::$app->language]);
                },
            ])
            ->andWhere(['{{%excursion_translation}}.language_code' => Yii::$app->language])
            ->andWhere([
                'or',
                ['{{%city_translation}}.name' => $q],
                ['{{%sight_translation}}.name' => $q]
            ])
            ->andWhere(['{{%excursion}}.status' => Excursion::STATUS_ACCEPTED])
            ->andWhere(['not', ['{{%excursion}}.user_id' => null]])
            ->addOrderBy('{{%excursion}}.created_at DESC')
            ->indexBy(function ($row) {
                return 'excursion-' . $row['id'];
            })
            ->groupBy('{{%excursion}}.id');

        if ($target_audience) {
            $query->joinWith('targetAudiences')
                ->andWhere(['{{%target_audience}}.url_alias' => $target_audience]);
        }

        $queryByTag = Excursion::find()
            ->select([
                '{{%excursion}}.*',
                'FROM_UNIXTIME({{%excursion}}.created_at, "%Y-%m-%d") AS publication_date'
            ])
            ->joinWith([
                'translations',
                'startCity',
                'startCity.translations' => function ($query) {
                    $query->onCondition(['{{%city_translation}}.language_code' => Yii::$app->language]);
                },
                'sights',
                'sights.translations' => function ($query) {
                    $query->onCondition(['{{%sight_translation}}.language_code' => Yii::$app->language]);
                },
            ])
            ->andWhere(['{{%excursion_translation}}.language_code' => Yii::$app->language])
            ->andWhere(['{{%excursion}}.status' => Excursion::STATUS_ACCEPTED])
            ->andWhere(['not', ['{{%excursion}}.user_id' => null]])
            ->addOrderBy('{{%excursion}}.created_at DESC')
            ->indexBy(function ($row) {
                return 'excursion-' . $row['id'];
            })
            ->groupBy('{{%excursion}}.id');
        ;

        if ($target_audience) {
            $queryByTag->joinWith('targetAudiences')
                ->andWhere(['{{%target_audience}}.url_alias' => $target_audience]);
        }

        $queryByTag
            ->anyTagValues($q)
        ;

        $queryAll = $query->union($queryByTag);

        $totalCount = $queryAll->count();

        $excursionList = $queryAll
            ->all();

        return [
            'count'      => $totalCount,
            'list'       => $excursionList,
        ];
    }

    protected function setPostQueryDefaults($query, $target_audience, $post_category)
    {
        $query
            ->andWhere(['{{%post_category}}.url_alias' => $post_category])
            ->andWhere(['{{%post}}.language_code' => Yii::$app->language])
            ->andWhere(['{{%post}}.status' => Post::STATUS_ACCEPTED])
            ->joinWith('postCategories')
            ->addOrderBy('{{%post}}.publication_date DESC')
            ->indexBy(function ($row) {
                return 'post-' . $row['id'];
            })
            ->groupBy('{{%post}}.id')
        ;
        if ($target_audience) {
            $query
                ->joinWith('targetAudiences')
                ->andWhere(['{{%target_audience}}.url_alias' => $target_audience])
            ;
        }
    }

    protected function composePostQuery($q, $target_audience, $post_category)
    {
        $queryByCity = Post::find();
        $this->setPostQueryDefaults($queryByCity, $target_audience, $post_category);
        $queryByCity
            ->joinWith('postSubject')
            ->innerJoin('{{%city}}', '{{%city}}.id = {{%post_subject}}.model_id')
            ->innerJoin('{{%city_translation}}', '{{%city}}.id = {{%city_translation}}.city_id')
            ->andWhere(['{{%post_subject}}.model_class' => PostSubject::MODEL_CLASS_CITY])
            ->andWhere(['{{%city_translation}}.name' => $q])
        ;


        $queryBySight = Post::find();
        $this->setPostQueryDefaults($queryBySight, $target_audience, $post_category);
        $queryBySight
            ->joinWith('postSubject')
            ->innerJoin('{{%sight}}', '{{%sight}}.id = {{%post_subject}}.model_id')
            ->innerJoin('{{%sight_translation}}', '{{%sight}}.id = {{%sight_translation}}.sight_id')
            ->andWhere(['{{%post_subject}}.model_class' => PostSubject::MODEL_CLASS_SIGHT])
            ->andWhere(['{{%sight_translation}}.name' => $q])
        ;

        $queryByTag = Post::find();
        $this->setPostQueryDefaults($queryByTag, $target_audience, $post_category);
        $queryByTag
            ->anyTagValues($q)
        ;

        $query = $queryByCity->union($queryBySight)->union($queryByTag);

        return $query;
    }

    public function searchPostsSight($target_audience = null)
    {
        $q = $this->q;

        if (empty($q)) {
            return [
                'count'      => 0,
                'list'       => [],
            ];
        }

        $query = $this->composePostQuery($q, $target_audience, PostCategory::ALIAS_SIGHT);

        $totalCount = $query->count();

        $postList = $query
            ->all();

        return [
            'count'      => $totalCount,
            'list'       => $postList,
        ];
    }

    public function searchPostsNews($target_audience = null)
    {
        $q = $this->q;

        if (empty($q)) {
            return [
                'count'      => 0,
                'list'       => [],
            ];
        }

        $query = $this->composePostQuery($q, $target_audience, PostCategory::ALIAS_NEWS);

        $totalCount = $query->count();

        $postList = $query
            ->all();

        return [
            'count'      => $totalCount,
            'list'       => $postList,
        ];
    }

    public function searchPostsLifehack($target_audience = null)
    {
        $q = $this->q;

        if (empty($q)) {
            return [
                'count'      => 0,
                'list'       => [],
            ];
        }

        $query = $this->composePostQuery($q, $target_audience, PostCategory::ALIAS_LIFEHACK);

        $totalCount = $query->count();

        $postList = $query
            ->all();

        return [
            'count'      => $totalCount,
            'list'       => $postList,
        ];
    }

    public function searchPostsExperience($target_audience = null)
    {
        $q = $this->q;

        if (empty($q)) {
            return [
                'count'      => 0,
                'list'       => [],
            ];
        }

        $query = $this->composePostQuery($q, $target_audience, PostCategory::ALIAS_EXPERIENCE);

        $totalCount = $query->count();

        $postList = $query
            ->all();

        return [
            'count'      => $totalCount,
            'list'       => $postList,
        ];
    }
}
