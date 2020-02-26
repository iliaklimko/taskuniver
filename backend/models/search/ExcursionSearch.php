<?php

namespace backend\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\Excursion;

/**
 * ExcursionSearch represents the model behind the search form about `common\models\Excursion`.
 */
class ExcursionSearch extends Excursion
{
    const DELIMITER = ' - ';

    public $publication_date_range;

    public $excursion_title;
    public $excursion_description;
    public $excursion_start_city;
    public $excursion_user;
    public $excursion_audience;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'user_id', 'created_at', 'updated_at', 'status', 'excursion_audience'], 'integer'],
            [['excursion_title', 'excursion_description', 'excursion_start_city', 'excursion_user', 'excursion_price_status'], 'safe'],
            [['publication_date_range', 'publication_date'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = Excursion::find()
            ->select([
                '{{%excursion}}.*',
                'IF({{%excursion}}.current_price > 0, 1, 0) AS excursion_price_status',
                'FROM_UNIXTIME({{%excursion}}.created_at, "%Y-%m-%d") AS publication_date'
            ]);

        // add conditions that should always apply here

        $query->joinWith([
            'translations',
            'startCity',
            'startCity.translations',
            'user',
            'targetAudiences'
        ]);

        $query->groupBy('{{%excursion}}.id');

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => [
                    'created_at' => SORT_DESC,
                ]
            ],
            'pagination' => [
                'pageSize' => 10,
            ],
        ]);

        $dataProvider->sort->attributes['publication_date'] = [
            'asc' => ['FROM_UNIXTIME({{%excursion}}.created_at, "%Y-%m-%d")' => SORT_ASC],
            'desc' => ['FROM_UNIXTIME({{%excursion}}.created_at, "%Y-%m-%d")' => SORT_DESC],
        ];

        $dataProvider->sort->attributes['excursion_title'] = [
            'asc' => ['{{%excursion_translation}}.title' => SORT_ASC],
            'desc' => ['{{%excursion_translation}}.title' => SORT_DESC],
        ];

        $dataProvider->sort->attributes['excursion_description'] = [
            'asc' => ['{{%excursion_translation}}.description' => SORT_ASC],
            'desc' => ['{{%excursion_translation}}.description' => SORT_DESC],
        ];

        $dataProvider->sort->attributes['excursion_start_city'] = [
            'asc' => ['{{%city_translation}}.name' => SORT_ASC],
            'desc' => ['{{%city_translation}}.name' => SORT_DESC],
        ];

        $dataProvider->sort->attributes['excursion_user'] = [
            'asc' => ['{{%user}}.full_name' => SORT_ASC],
            'desc' => ['{{%user}}.fullname' => SORT_DESC],
        ];

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            '{{%excursion}}.id' => $this->id,
            '{{%excursion}}.status' => $this->status,
            'user_id' => $this->user_id,
            '{{%excursion__target_audience}}.target_audience_id' => $this->excursion_audience,
        ]);

        $query->andFilterWhere(['like', '{{%excursion_translation}}.title', $this->excursion_title]);
        $query->andFilterWhere(['like', '{{%excursion_translation}}.description', $this->excursion_description]);
        $query->andFilterWhere(['like', '{{%city_translation}}.name', $this->excursion_start_city]);
        $query->andFilterWhere(['like', '{{%user}}.full_name', $this->excursion_user]);

        if ($this->excursion_price_status != '') {
            if ($this->excursion_price_status == 1) {
                $query->andFilterWhere(['>', '{{%excursion}}.current_price', 0]);
            }
            if ($this->excursion_price_status == 0) {
                $query->andFilterWhere(['not', ['>', '{{%excursion}}.current_price', 0]]);
            }
        }

        $this->addPublicationDateRangeFilter($query);

        return $dataProvider;
    }

    protected function addPublicationDateRangeFilter($query)
    {
        if ($this->publication_date_range) {
            $parsedDateRange = explode(self::DELIMITER, $this->publication_date_range);
            $query->andFilterWhere(['between', 'FROM_UNIXTIME({{%excursion}}.created_at, "%Y-%m-%d")', $parsedDateRange[0], $parsedDateRange[1]]);
        }
    }

    /** @inheritdoc */
    public function beforeValidate()
    {
        return true;
    }
}
