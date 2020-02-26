<?php

namespace backend\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\Order;

/**
 * OrderSearch represents the model behind the search form about `common\models\Order`.
 */
class OrderSearch extends Order
{
    const DELIMITER = ' - ';

    public $order_date_range;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'excursion_id', 'quantity', 'created_at', 'guide_status', 'paid_to_guide'], 'integer'],
            [['code', 'name', 'email', 'phone', 'status'], 'safe'],
            [['order_date_range', 'order_date'], 'safe'],
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
        $query = Order::find()
            ->select([
                '{{%order}}.*',
                'FROM_UNIXTIME({{%order}}.created_at, "%Y-%m-%d") AS order_date'
            ]);
        $query->andWhere(['{{%order}}.status' => $this->status]);
        $query->andWhere(['not', ['{{%order}}.excursion_id' => null]]);

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => [
                    'created_at' => SORT_DESC,
                ]
            ],
        ]);

        $dataProvider->sort->attributes['order_date'] = [
            'asc' => ['FROM_UNIXTIME({{%order}}.created_at, "%Y-%m-%d")' => SORT_ASC],
            'desc' => ['FROM_UNIXTIME({{%order}}.created_at, "%Y-%m-%d")' => SORT_DESC],
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
            'excursion_id' => $this->excursion_id,
            'quantity' => $this->quantity,
            'guide_status' => $this->guide_status,
            'paid_to_guide' => $this->paid_to_guide,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name]);
        $query->andFilterWhere(['like', 'code', $this->code]);
        $query->andFilterWhere(['like', 'email', $this->email]);
        $query->andFilterWhere(['like', 'phone', $this->phone]);

        $this->addDateRangeFilter($query);

        return $dataProvider;
    }

    protected function addDateRangeFilter($query)
    {
        if ($this->order_date_range) {
            $parsedDateRange = explode(self::DELIMITER, $this->order_date_range);
            $query->andFilterWhere(['between', 'FROM_UNIXTIME({{%order}}.created_at, "%Y-%m-%d")', $parsedDateRange[0], $parsedDateRange[1]]);
        }
    }
}
