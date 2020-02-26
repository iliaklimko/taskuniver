<?php

namespace backend\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\FooterMenuItem as FooterMenuItemModel;

/**
 * CitySearch represents the model behind the search form about `common\models\City`.
 */
class FooterMenuItemSearch extends FooterMenuItemModel
{
    public $menu_item_title;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'position'], 'integer'],
            [['menu_item_title', 'column'], 'safe'],
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
        $query = FooterMenuItemModel::find();

        // add conditions that should always apply here

        $query->joinWith('translations');

        $query->groupBy('{{%footer_menu_item}}.id');

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $dataProvider->sort->attributes['menu_item_title'] = [
            'asc' => ['{{%footer_menu_item_translation}}.name' => SORT_ASC],
            'desc' => ['{{%footer_menu_item_translation}}.name' => SORT_DESC],
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
            'column' => $this->column,
        ]);

        $query->andFilterWhere(['like', '{{%footer_menu_item_translation}}.title', $this->menu_item_title]);

        return $dataProvider;
    }
}
