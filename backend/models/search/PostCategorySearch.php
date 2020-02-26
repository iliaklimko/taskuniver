<?php

namespace backend\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\PostCategory;

/**
 * PostCategorySearch represents the model behind the search form about `common\models\PostCategory`.
 */
class PostCategorySearch extends PostCategory
{
    public $post_category_name;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'priority'], 'integer'],
            [['post_category_name', 'url_alias'], 'safe'],
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
        $query = PostCategory::find();

        // add conditions that should always apply here

        $query->joinWith('translations');

        $query->groupBy('{{%post_category}}.id');

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => [
                    'priority' => SORT_DESC,
                ]
            ],
        ]);

        $dataProvider->sort->attributes['post_category_name'] = [
            'asc' => ['{{%post_category_translation}}.name' => SORT_ASC],
            'desc' => ['{{%post_category_translation}}.name' => SORT_DESC],
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

        $query->andFilterWhere(['like', '{{%post_category_translation}}.name', $this->post_category_name]);
        $query->andFilterWhere(['like', 'url_alias', $this->url_alias]);

        return $dataProvider;
    }
}
