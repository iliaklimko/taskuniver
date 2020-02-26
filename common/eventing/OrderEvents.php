<?php

namespace common\eventing;

class OrderEvents
{
    const CHARGED      = 'order.created';
    const GUIDE_ACCEPT = 'order.guide.accept';
    const GUIDE_REJECT = 'order.guide.reject';

    const PAID_TO_GUIDE = 'order.paid.to.guide';
}
