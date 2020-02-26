<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%post_subject}}".
 *
 * @property integer $post_id
 * @property string $model_class
 * @property integer $model_id
 */
class PostSubject extends \yii\db\ActiveRecord
{
    const MODEL_CLASS_COUNTRY = 'common\models\Country';
    const MODEL_CLASS_CITY    = 'common\models\City';
    const MODEL_CLASS_SIGHT   = 'common\models\Sight';

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%post_subject}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['post_id', 'model_class', 'model_id'], 'required'],
            [['post_id', 'model_id'], 'integer'],
            [['model_class'], 'string', 'max' => 255],
        ];
    }
}
