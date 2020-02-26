<?php

namespace backend\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\ExcursionType;

/**
 * ExcursionTypeSearch represents the model behind the search form about `common\models\ExcursionType`.
 */
class ExcursionTypeSearch extends ExcursionType
{
    public $excursion_type_name;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'priority'], 'integer'],
            [['excursion_type_name'], 'safe'],
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
        $query = ExcursionType::find();

        // add conditions that should always apply here

        $query->joinWith('translations');

        $query->groupBy('{{%excursion_type}}.id');

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => [
                    'priority' => SORT_DESC,
                ]
            ],
        ]);

        $dataProvider->sort->attributes['excursion_type_name'] = [
            'asc' => ['{{%excursion_type_translation}}.name' => SORT_ASC],
            'desc' => ['{{%excursion_type_translation}}.name' => SORT_DESC],
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
            'priority' => $this->priority,
        ]);

        $query->andFilterWhere(['like', '{{%excursion_type_translation}}.name', $this->excursion_type_name]);

        return $dataProvider;
    }
}
