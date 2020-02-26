<?php

namespace backend\models\search\traits;

trait DateRangeForSearchModel
{
    public $delimiter = ' - ';

    public $date_range;

    public function getDateRangeRule()
    {
        return ['date_range', 'safe'];
    }

    public function addDateRangeFilter($query, $field)
    {
        if ($this->date_range) {
            $parsedDateRange = explode($this->delimiter, $this->date_range);
            $query->andFilterWhere(['between', $field, $parsedDateRange[0], $parsedDateRange[1]]);
        }
    }
}
