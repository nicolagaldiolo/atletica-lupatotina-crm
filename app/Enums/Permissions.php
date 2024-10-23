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

    // Maintenance
    const RunMaintenance = 'run_maintenance';

    // Athletes
    const ListAthletes = 'list_athletes';
    const ViewAthletes = 'view_athletes';
    const CreateAthletes = 'create_athletes';
    const EditAthletes = 'edit_athletes';
    const DeleteAthletes = 'delete_athletes';
    const ReportAthletes = 'report_athletes';
    const InviteAthletes = 'invite_athletes';

    // Vouchers
    const ListVouchers = 'list_vouchers';
    const ViewVouchers = 'view_vouchers';
    const CreateVouchers = 'create_vouchers';
    const EditVouchers = 'edit_vouchers';
    const DeleteVouchers = 'delete_vouchers';

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
    const ReportRaces = 'report_races';

    // Payments
    const HandlePayments = 'handle_payments';
    
    // Subscriptions
    const HandleSubscriptions = 'handle_subscriptions';

    // Users
    const ListUsers = 'list_users';
    const ViewUsers = 'view_users';
    const CreateUsers = 'create_users';
    const EditUsers = 'edit_users';
    const DeleteUsers = 'delete_users';
    const BlockUsers = 'block_users';

    // Roles
    const ListRoles = 'list_roles';
    const ViewRoles = 'view_roles';
    const CreateRoles = 'create_roles';
    const EditRoles = 'edit_roles';
    const DeleteRoles = 'delete_roles';
    const AssignRoles = 'assign_roles';
}
