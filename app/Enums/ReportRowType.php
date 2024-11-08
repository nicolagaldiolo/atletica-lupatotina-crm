<?php declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static OptionOne()
 * @method static static OptionTwo()
 * @method static static OptionThree()
 */
final class ReportRowType extends Enum
{
    const Subscription = 'Iscrizione';
    const Payment = 'Pagamento';
    const Voucher = 'Voucher';
}
