<?php declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Contracts\LocalizedEnum;
use BenSampo\Enum\Enum;

/**
 * @method static static OptionOne()
 * @method static static OptionTwo()
 * @method static static OptionThree()
 */
final class PaymentStatus extends Enum implements LocalizedEnum
{
    const NotPayed = 'not_payed';
    const PartialPayped = 'partial_payed';
    const Payed = 'payed';

    /**
     * Get the color associated with each state
     *
     * @return array
     */
    protected static function colors(): array
    {
        return [
            self::NotPayed => [
                'bg_class' => 'bg-danger text-white',
                'text_class' => 'text-danger',
                'icon' => 'fa-solid fa-triangle-exclamation'
            ],
            self::PartialPayped => [
                'bg_class' => 'bg-warning text-white',
                'text_class' => 'text-warning',
                'icon' => 'fa-solid fa-coins'
            ],
            self::Payed => [
                'bg_class' => 'bg-success text-white',
                'text_class' => 'text-success',
                'icon' => 'fa-solid fa-coins'
            ]
        ];
    }

    /**
     * Get the color for a specific state
     *
     * @param string $state
     * @return array|null
     */
    public static function getColor(string $type): ?array
    {
        return self::colors()[$type] ?? null;
    }
}