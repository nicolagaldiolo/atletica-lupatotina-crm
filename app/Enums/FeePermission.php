<?php declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static OptionOne()
 * @method static static OptionTwo()
 * @method static static OptionThree()
 */
final class FeePermission extends Enum
{
    const ListFees = 'list_fees';
    const ViewFees = 'view_fees';
    const CreateFees = 'create_fees';
    const EditFees = 'edit_fees';
    const DeleteFees = 'delete_fees';
    const RestoreFees = 'restore_fees';
}
