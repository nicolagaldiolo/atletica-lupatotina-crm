<?php declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static OptionOne()
 * @method static static OptionTwo()
 * @method static static OptionThree()
 */
final class AthletePermission extends Enum
{
    const ListAthletes = 'list_athletes';
    const ViewAthletes = 'view_athletes';
    const CreateAthletes = 'create_athletes';
    const EditAthletes = 'edit_athletes';
    const DeleteAthletes = 'delete_athletes';
    const RestoreAthletes = 'restore_athletes';
}
