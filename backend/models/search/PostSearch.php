<?php

namespace backend\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\Post;

/**
 * PostSearch represents the model behind the search form about `common\models\Post`.
 */
class PostSearch extends Post
{
    const DELIMITER = ' - ';

    public $author_name;
    public $publication_date_range;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'created_at', 'updated_at', 'status'], 'integer'],
            [['language_code', 'title', 'body', 'author_name', 'user_name'], 'safe'],
            [['publication_date_range'], 'safe'],
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
        $query = Post::find();

        $query->joinWith('author');

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => [
                    'publication_date' => SORT_DESC,
                ]
            ],
            'pagination' => [
                'pageSize' => 10,
            ],
        ]);

        $dataProvider->sort->attributes['author_name'] = [
            'asc' => ['{{%user}}.full_name' => SORT_ASC],
            'desc' => ['{{%user}}.full_name' => SORT_DESC],
        ];

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            '{{%post}}.id' => $this->id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            '{{%post}}.status' => $this->status,
        ]);

        $query->andFilterWhere(['like', 'language_code', $this->language_code])
            ->andFilterWhere(['like', 'title', $this->title])
            ->andFilterWhere(['like', 'body', $this->body])
            ->andFilterWhere(['like', '{{%user}}.full_name', $this->author_name]);

        $query->andFilterWhere(['like', 'user_name', $this->user_name]);

        $this->addPublicationDateRangeFilter($query);

        return $dataProvider;
    }

    protected function addPublicationDateRangeFilter($query)
    {
        if ($this->publication_date_range) {
            $parsedDateRange = explode(self::DELIMITER, $this->publication_date_range);
            $query->andFilterWhere(['between', 'publication_date', $parsedDateRange[0], $parsedDateRange[1]]);
        }
    }
}
