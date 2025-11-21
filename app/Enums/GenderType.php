<?php declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Contracts\LocalizedEnum;
use BenSampo\Enum\Enum;

/**
 * @method static static OptionOne()
 * @method static static OptionTwo()
 * @method static static OptionThree()
 */
final class GenderType extends Enum implements LocalizedEnum
{
    const Male = 0;
    const Female = 1;

    /**
     * Get the color associated with each state
     *
     * @return array
     */
    protected static function colors(): array
    {
        return [
            self::Male => '#9FC5E8',
            self::Female => '#ffbff1'
        ];
    }

    /**
     * Get the color for a specific state
     *
     * @param int $state
     * @return string|null
     */
    public static function getColor(int $type): ?string
    {
        return self::colors()[$type] ?? null;
    }
}
