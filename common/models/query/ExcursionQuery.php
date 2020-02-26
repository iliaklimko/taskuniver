<?php

namespace common\models\query;

use creocoder\taggable\TaggableQueryBehavior;

class ExcursionQuery extends \yii\db\ActiveQuery
{
    public function behaviors()
    {
        return [
            TaggableQueryBehavior::className(),
        ];
    }
}
