<?php

namespace common\eventing\events;

use yii\base\Event;

class OrderChargedEvent extends Event
{
    public $orderId;
}
