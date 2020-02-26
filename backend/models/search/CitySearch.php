<?php

namespace backend\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\City as CityModel;

/**
 * CitySearch represents the model behind the search form about `common\models\City`.
 */
class CitySearch extends CityModel
{
    public $city_name;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'country_id'], 'integer'],
            [['city_name'], 'safe'],
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
        $query = CityModel::find();

        // add conditions that should always apply here

        $query->joinWith('translations');

        $query->groupBy('{{%city}}.id');

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $dataProvider->sort->attributes['city_name'] = [
            'asc' => ['{{%city_translation}}.name' => SORT_ASC],
            'desc' => ['{{%city_translation}}.name' => SORT_DESC],
        ];

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'country_id' => $this->country_id,
        ]);

        $query->andFilterWhere(['like', '{{%city_translation}}.name', $this->city_name]);

        return $dataProvider;
    }
}
