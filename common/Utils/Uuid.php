<?php

namespace Common\Utils;

use Ramsey\Uuid\Uuid as RamseyUuid;

class Uuid
{
    public static function generate(): string
    {
        return RamseyUuid::uuid4()->toString();
    }

    public static function isValid(string $uuid): bool
    {
        return RamseyUuid::isValid($uuid);
    }
}
