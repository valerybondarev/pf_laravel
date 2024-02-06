<?php

namespace App\Enum;

class OrderTypes
{
    public const CLIENT_TYPE = 'client';
    public const AGENT_TYPE = 'agent';
    public const TYPES = [self::CLIENT_TYPE, self::AGENT_TYPE];
}
