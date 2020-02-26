<?php

namespace common\eventing\events;

use yii\base\Event;

class OrderGuideAcceptEvent extends Event
{
    public $orderId;
}
