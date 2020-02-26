<?php

namespace common\eventing\events;

use yii\base\Event;
use common\models\Excursion;

class ExcursionRejectedEvent extends Event
{
    public $excursionId;
}
