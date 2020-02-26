<?php

namespace common\eventing\events;

use yii\base\Event;
use common\models\Excursion;

class ExcursionCreatedEvent extends Event
{
    public $excursionId;
}
