<?php declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static OptionOne()
 * @method static static OptionTwo()
 * @method static static OptionThree()
 */
final class RacePermission extends Enum
{
    const ListRaces = 'list_races';
    const ViewRaces = 'view_races';
    const CreateRaces = 'create_races';
    const EditRaces = 'edit_races';
    const DeleteRaces = 'delete_races';
    const RestoreRaces = 'restore_races';
}
