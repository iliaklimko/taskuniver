<?php

namespace common\components\validators;

use yii\validators\Validator;

class TagValuesUniqueValidator extends Validator
{
    /**
     * @inheritdoc
     */
    public function validateAttribute($model, $attribute)
    {
        $value = $model->$attribute;
        if (is_array($value)) {
            $value = array_unique(
                array_map(function ($v) {
                    return strtolower($v);
                }, $value)
            );
            $model->$attribute = $value;
        }
    }
}
