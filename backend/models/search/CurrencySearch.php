<?php

namespace backend\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Currency;

/**
 * CurrencySearch represents the model behind the search form about `common\models\Currency`.
 */
class CurrencySearch extends Currency
{
    public $currency_name;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'base'], 'integer'],
            [['code', 'currency_name'], 'safe'],
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
        $query = Currency::find();

        // add conditions that should always apply here

        $query->joinWith('translations');

        $query->groupBy('{{%currency}}.id');

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $dataProvider->sort->attributes['currency_name'] = [
            'asc' => ['{{%currency_translation}}.name' => SORT_ASC],
            'desc' => ['{{%currency_translation}}.name' => SORT_DESC],
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
            'base' => $this->base,
        ]);

        $query->andFilterWhere(['like', 'code', $this->code]);

        $query->andFilterWhere(['like', '{{%currency_translation}}.name', $this->currency_name]);

        return $dataProvider;
    }
}
