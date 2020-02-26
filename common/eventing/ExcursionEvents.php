<?php

namespace common\eventing;

class ExcursionEvents
{
    const EXCURSION_CREATED = 'excursion.created';
    const EXCURSION_REJECTED = 'excursion.rejected';
    const EXCURSION_ACCEPTED = 'excursion.accepted';
    const EXCURSION_CREATED_BY_ADMIN = 'excursion.created_by_admin';
    const EXCURSION_UPDATED_BY_OWNER = 'excursion.updated_by_owner';
}
