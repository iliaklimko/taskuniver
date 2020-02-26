<?php

namespace common\eventing\events;

use yii\base\Event;

class UserCreatedEvent extends Event
{
    public $userId;
}
