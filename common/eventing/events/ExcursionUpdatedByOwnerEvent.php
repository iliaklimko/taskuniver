<?php

namespace common\eventing\events;

use yii\base\Event;
use common\models\Excursion;

class ExcursionUpdatedByOwnerEvent extends Event
{
    public $excursionId;
}
