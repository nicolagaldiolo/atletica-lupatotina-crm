<?php declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static OptionOne()
 * @method static static OptionTwo()
 * @method static static OptionThree()
 */
final class Roles extends Enum
{
    const SuperAdmin = 'superadmin';
    const Administrator = 'administrator';
    const Manager = 'manager';
    const Accountant = 'accountant';
    const Healthcare = 'healthcare';
    const User = 'user';
}
