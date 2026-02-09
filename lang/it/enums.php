<?php declare(strict_types=1);

use App\Enums\GenderType;
use App\Enums\MemberType;
use App\Enums\OrderRowStatus;
use App\Enums\OrderStatus;
use App\Enums\PaymentStatus;
use App\Enums\RaceType;
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
    ],

    RaceType::class => [
        RaceType::Race => 'Gara',
        RaceType::Track => 'Pista',
        RaceType::Clothes => 'Abbigliamento'
    ],

    OrderStatus::class => [
        OrderStatus::Pending => 'In Attesa di conferma',
        OrderStatus::Processing => 'In lavorazione',
        OrderStatus::Partially_delivered => 'Parzialmente consegnato',
        OrderStatus::Delivered => 'Consegnato',
        OrderStatus::Canceled => 'Annullato',
    ],

    OrderRowStatus::class => [
        OrderRowStatus::Pending => 'In Attesa di conferma',
        OrderRowStatus::Processing => 'In lavorazione',
        OrderRowStatus::Delivered => 'Consegnato',
        OrderRowStatus::Canceled => 'Annullato'
    ],

    PaymentStatus::class => [
        PaymentStatus::NotPayed => 'Non pagato',
        PaymentStatus::PartialPayped => 'Parzialemente Pagato',
        PaymentStatus::Payed => 'Pagato'
    ]
];
