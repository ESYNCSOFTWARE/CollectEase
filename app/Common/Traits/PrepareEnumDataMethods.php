<?php

declare(strict_types=1);

namespace App\Common\Traits;

use App\CommonFunctions;
use Illuminate\Support\Collection;

trait PrepareEnumDataMethods
{
    public static function getValues(): string
    {
        return (new Collection(self::cases()))->pluck('value')->implode(',');
    }

    /**
     * @return mixed[]
     */
    public static function getList(): array
    {
        return (new Collection(self::cases()))->map(fn ($type): array => [
            'id' => $type->value,
            'name' => CommonFunctions::stringTitleLowerCase($type->name),
            'key' => $type->name,
        ])->toArray();
    }

    /**
     * @return mixed[]
     */
    public static function formattedForSelection(bool $nameInTitleCase = true): array
    {
        return (new Collection(self::cases()))->map(fn ($type): array => [
            'id' => $type->value,
            'name' => $nameInTitleCase ? CommonFunctions::stringTitleLowerCase($type->name) : $type->name,
        ])->toArray();
    }

    public static function getFormattedCaseName(int|string $key): string
    {
        /** @var self $case */
        $case = self::tryFrom($key);

        return CommonFunctions::stringTitleLowerCase($case->name);
    }
}
