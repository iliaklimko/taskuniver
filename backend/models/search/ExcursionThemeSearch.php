<?php

namespace backend\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\ExcursionTheme;

/**
 * ExcursionThemeSearch represents the model behind the search form about `common\models\ExcursionTheme`.
 */
class ExcursionThemeSearch extends ExcursionTheme
{
    public $excursion_theme_name;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'priority'], 'integer'],
            [['excursion_theme_name'], 'safe'],
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
        $query = ExcursionTheme::find();

        // add conditions that should always apply here

        $query->joinWith('translations');

        $query->groupBy('{{%excursion_theme}}.id');

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => [
                    'priority' => SORT_DESC,
                ]
            ],
        ]);

        $dataProvider->sort->attributes['excursion_theme_name'] = [
            'asc' => ['{{%excursion_theme_translation}}.name' => SORT_ASC],
            'desc' => ['{{%excursion_theme_translation}}.name' => SORT_DESC],
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

        $query->andFilterWhere(['like', '{{%excursion_theme_translation}}.name', $this->excursion_theme_name]);

        return $dataProvider;
    }
}
