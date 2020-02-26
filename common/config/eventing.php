<?php

use common\eventing\UserEvents;
use common\eventing\ExcursionEvents;
use common\eventing\OrderEvents;

$on = function ($e) {
    return "on $e";
};

// php 5.4
$userListenerClass      = 'common\eventing\listeners\UserListener';
$excursionListenerClass = 'common\eventing\listeners\ExcursionListener';
$orderListenerClass     = 'common\eventing\listeners\OrderListener';

return [
    $on(UserEvents::USER_CREATED)     => [$userListenerClass, 'handleCreation'],
    $on(UserEvents::PASSWORD_CHANGED) => [$userListenerClass, 'handlePasswordChange'],

    $on(ExcursionEvents::EXCURSION_CREATED)          => [$excursionListenerClass, 'handleCreation'],
    $on(ExcursionEvents::EXCURSION_REJECTED)         => [$excursionListenerClass, 'handleReject'],
    $on(ExcursionEvents::EXCURSION_ACCEPTED)         => [$excursionListenerClass, 'handleAccept'],
    $on(ExcursionEvents::EXCURSION_CREATED_BY_ADMIN) => [$excursionListenerClass, 'handleCreatedByAdmin'],
    $on(ExcursionEvents::EXCURSION_UPDATED_BY_OWNER) => [$excursionListenerClass, 'handleUpdatedByOwner'],

    $on(OrderEvents::CHARGED)       => [$orderListenerClass, 'handleCharge'],
    $on(OrderEvents::GUIDE_ACCEPT)  => [$orderListenerClass, 'handleGuideAccept'],
    $on(OrderEvents::GUIDE_REJECT)  => [$orderListenerClass, 'handleGuideReject'],
    $on(OrderEvents::PAID_TO_GUIDE) => [$orderListenerClass, 'handlePaidToGuide'],
];
