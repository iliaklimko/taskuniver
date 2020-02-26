<?php

namespace backend\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Sight;

/**
 * SightSearch represents the model behind the search form about `common\models\Sight`.
 */
class SightSearch extends Sight
{
    public $sight_name;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'city_id'], 'integer'],
            [['sight_name'], 'safe'],
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
        $query = Sight::find();

        // add conditions that should always apply here

        $query->joinWith('translations');

        $query->groupBy('{{%sight}}.id');

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $dataProvider->sort->attributes['sight_name'] = [
            'asc' => ['{{%sight_translation}}.name' => SORT_ASC],
            'desc' => ['{{%sight_translation}}.name' => SORT_DESC],
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
            'city_id' => $this->city_id,
        ]);

        $query->andFilterWhere(['like', '{{%sight_translation}}.name', $this->sight_name]);

        return $dataProvider;
    }
}
