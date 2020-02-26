<?php

namespace backend\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\EmailTemplate as EmailTemplateModel;

/**
 * EmailTemplateSearch represents the model behind the search form about `common\models\EmailTemplate`.
 */
class EmailTemplateSearch extends EmailTemplateModel
{
    public $email_template_subject;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id'], 'integer'],
            [['alias', 'email_template_subject'], 'safe'],
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
        $query = EmailTemplateModel::find();

        // add conditions that should always apply here

        $query->joinWith('translations');

        $query->groupBy('{{%email_template}}.id');

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $dataProvider->sort->attributes['email_template_subject'] = [
            'asc'  => ['{{%email_template_translation}}.subject' => SORT_ASC],
            'desc' => ['{{%email_template_translation}}.subject' => SORT_DESC],
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

        $query->andFilterWhere(['like', '{{%email_template}}.alias', $this->alias]);
        $query->andFilterWhere(['like', '{{%email_template_translation}}.subject', $this->email_template_subject]);

        return $dataProvider;
    }
}
