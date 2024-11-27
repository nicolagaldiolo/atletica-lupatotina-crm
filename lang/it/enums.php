<?php declare(strict_types=1);

use App\Enums\GenderType;
use App\Enums\MemberType;
use App\Enums\VoucherType;

return [

    GenderType::class => [
        GenderType::Male => 'Uomo',
        GenderType::Female => 'Donna',
        GenderType::Other => 'Altro',
    ],

    VoucherType::class => [
        VoucherType::Credit => 'Credito',
        VoucherType::Penalty => 'Penalità',
    ],

    MemberType::class => [
        MemberType::Athlete => 'Atleta',
        MemberType::Supporter => 'Simpatizzante',
    ]

];
