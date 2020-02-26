<?php

namespace common\eventing\events;

use yii\base\Event;

class OrderPaidToGuideEvent extends Event
{
    public $orderId;
}
