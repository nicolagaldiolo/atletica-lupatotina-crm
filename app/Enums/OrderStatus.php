<?php declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Contracts\LocalizedEnum;
use BenSampo\Enum\Enum;

/**
 * @method static static OptionOne()
 * @method static static OptionTwo()
 * @method static static OptionThree()
 */
final class OrderStatus extends Enum implements LocalizedEnum
{
    const Pending = 'pending';
    const Processing = 'processing';
    const Partially_delivered = 'partially_delivered';
    const Delivered = 'delivered';
    const Canceled = 'canceled';

    /**
     * Get the color associated with each state
     *
     * @return array
     */
    protected static function colors(): array
    {
        return [
            self::Pending => [
                'bg_class' => 'bg-primary text-white',
                'text_class' => 'text-primary',
                'icon' => 'fas fa-shopping-cart'
            ],
            self::Processing => [
                'bg_class' => 'bg-info text-white',
                'text_class' => 'text-info',
                'icon' => 'fas fa-cogs'
            ],
            self::Partially_delivered => [
                'bg_class' => 'bg-warning text-white',
                'text_class' => 'text-warning',
                'icon' => 'fas fa-truck-loading'
            ],
            self::Delivered => [
                'bg_class' => 'bg-success text-white',
                'text_class' => 'text-success',
                'icon' => 'fas fa-truck'
            ],
            self::Canceled => [
                'bg_class' => 'bg-secondary text-white',
                'text_class' => 'text-secondary',
                'icon' => 'fas fa-ban'
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