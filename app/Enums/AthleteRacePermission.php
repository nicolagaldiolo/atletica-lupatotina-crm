<?php declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static OptionOne()
 * @method static static OptionTwo()
 * @method static static OptionThree()
 */
final class AthleteRacePermission extends Enum
{
    const ListAthleteRaces = 'list_athlete_races';
    const ViewAthleteRaces = 'view_athlete_races';
    const CreateAthleteRaces = 'create_athlete_races';
    const EditAthleteRaces = 'edit_athlete_races';
    const DeleteAthleteRaces = 'delete_athlete_races';
    const RestoreAthleteRaces = 'restore_athlete_races';
}
