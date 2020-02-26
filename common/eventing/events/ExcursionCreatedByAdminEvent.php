<?php

namespace common\eventing\events;

use yii\base\Event;

class ExcursionCreatedByAdminEvent extends Event
{
    public $excursionId;
}
