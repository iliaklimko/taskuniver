<?php

namespace backend\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\TargetAudience;

/**
 * TargetAudienceSearch represents the model behind the search form about `common\models\TargetAudience`.
 */
class TargetAudienceSearch extends TargetAudience
{
    public $target_audience_name;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'priority'], 'integer'],
            [['target_audience_name', 'url_alias'], 'safe'],
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
        $query = TargetAudience::find();

        // add conditions that should always apply here

        $query->joinWith('translations');

        $query->groupBy('{{%target_audience}}.id');

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => [
                    'priority' => SORT_DESC,
                ]
            ],
        ]);

        $dataProvider->sort->attributes['target_audience_name'] = [
            'asc' => ['{{%target_audience_translation}}.name' => SORT_ASC],
            'desc' => ['{{%target_audience_translation}}.name' => SORT_DESC],
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

        $query->andFilterWhere(['like', '{{%target_audience_translation}}.name', $this->target_audience_name]);
        $query->andFilterWhere(['like', 'url_alias', $this->url_alias]);

        return $dataProvider;
    }
}
