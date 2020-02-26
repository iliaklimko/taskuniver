<?php

namespace backend\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\ExcursionGroups;

/**
 * ExcursionGroupsSearch represents the model behind the search form about `common\models\ExcursionGroups`.
 */
class ExcursionGroupsSearch extends ExcursionGroups
{
    public $excursion_groups_name;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'priority'], 'integer'],
            [['excursion_groups_name'], 'safe'],
            [['code'], 'string'],
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
        $query = ExcursionGroups::find();

        // add conditions that should always apply here

        $query->joinWith('translations');

        $query->groupBy('{{%excursion_groups}}.id');

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => [
                    'priority' => SORT_DESC,
                ]
            ],
        ]);

        $dataProvider->sort->attributes['excursion_groups_name'] = [
            'asc' => ['{{%excursion_groups_translation}}.name' => SORT_ASC],
            'desc' => ['{{%excursion_groups_translation}}.name' => SORT_DESC],
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
            'code' => $this->code
        ]);

        $query->andFilterWhere(['like', '{{%excursion_groups_translation}}.name', $this->excursion_groups_name]);

        return $dataProvider;
    }
}
