<?php

namespace common\components\email;

interface EmailTemplateInterface
{
    public function getSubject();
    public function getBody();
}
