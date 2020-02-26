<?php

namespace common\eventing\events;

use yii\base\Event;

class UserPasswordChangedEvent extends Event
{
    public $userId;
}
