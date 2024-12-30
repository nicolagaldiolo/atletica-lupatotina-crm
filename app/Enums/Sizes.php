<?php declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static OptionOne()
 * @method static static OptionTwo()
 * @method static static OptionThree()
 */
final class Sizes extends Enum
{
    const ONESIZE = 'onesize';
    const XXS = 'xxs';
    const XS = 'xs';
    const S = 's';
    const M = 'm';
    const L = 'l';
    const XL = 'xl';
    const XXL = 'xxl';
}
