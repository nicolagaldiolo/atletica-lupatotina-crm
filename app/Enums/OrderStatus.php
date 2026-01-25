<?php declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static OptionOne()
 * @method static static OptionTwo()
 * @method static static OptionThree()
 */
final class OrderStatus extends Enum
{
    const Pending = 'pending';
    const Processing = 'processing';
    const Partially_delivered = 'partially_delivered';
    const Delivered = 'delivered';
    const Canceled = 'canceled';
}