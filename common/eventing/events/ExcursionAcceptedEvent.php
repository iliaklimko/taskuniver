<?php

namespace common\eventing\events;

use yii\base\Event;
use common\models\Excursion;

class ExcursionAcceptedEvent extends Event
{
    public $excursionId;
}
