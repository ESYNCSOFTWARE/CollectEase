<?php

declare(strict_types=1);

namespace App;

use Illuminate\Support\Str;

class CommonFunctions
{
    public static function stringTitleLowerCase(string $name): string
    {
        return Str::title(str_replace('_', ' ', $name));
    }
}
