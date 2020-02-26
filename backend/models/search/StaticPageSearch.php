<?php

namespace backend\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\StaticPage;

/**
 * StaticPageSearch represents the model behind the search form about `common\models\StaticPage`.
 */
class StaticPageSearch extends StaticPage
{
    public $static_page_title;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id'], 'integer'],
            [['static_page_title'], 'safe'],
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
        $query = StaticPage::find();

        // add conditions that should always apply here

        $query->joinWith('translations');

        $query->groupBy('{{%static_page}}.id');

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $dataProvider->sort->attributes['static_page_title'] = [
            'asc' => ['{{%static_page_translation}}.title' => SORT_ASC],
            'desc' => ['{{%static_page_translation}}.title' => SORT_DESC],
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
        ]);

        $query->andFilterWhere(['like', '{{%static_page_translation}}.title', $this->static_page_title]);

        return $dataProvider;
    }
}
