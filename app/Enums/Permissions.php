<?php declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static OptionOne()
 * @method static static OptionTwo()
 * @method static static OptionThree()
 */
final class Permissions extends Enum
{

    // General
    const ViewDashboard = 'view_dashboard';

    // Athletes
    const ListAthletes = 'list_athletes';
    const ViewAthletes = 'view_athletes';
    const CreateAthletes = 'create_athletes';
    const EditAthletes = 'edit_athletes';
    const DeleteAthletes = 'delete_athletes';

    // Certificates
    const ListCertificates = 'list_certificates';
    const ViewCertificates = 'view_certificates';
    const CreateCertificates = 'create_certificates';
    const EditCertificates = 'edit_certificates';
    const DeleteCertificates = 'delete_certificates';

    // Race
    const ListRaces = 'list_races';
    const ViewRaces = 'view_races';
    const CreateRaces = 'create_races';
    const EditRaces = 'edit_races';
    const DeleteRaces = 'delete_races';

    // Payments
    const HandlePayments = 'handle_payments';
    
    // Subscriptions
    const HandleSubscriptions = 'handle_subscriptions';

    // Users
    const ViewUsers = 'view_users';
    const AddUsers = 'add_users';
    const EditUsers = 'edit_users';
    const DeleteUsers = 'delete_users';
    const BlockUsers = 'block_users';

    // Roles
    const ViewRoles = 'view_roles';
    const AddRoles = 'add_roles';
    const EditRoles = 'edit_roles';
    const DeleteRoles = 'delete_roles';
}
