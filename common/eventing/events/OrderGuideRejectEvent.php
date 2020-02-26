<?php

namespace common\eventing\events;

use yii\base\Event;

class OrderGuideRejectEvent extends Event
{
    public $orderId;
}
