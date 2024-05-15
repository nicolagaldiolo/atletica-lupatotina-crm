<?php declare(strict_types=1);

use App\Enums\GenderType;

return [

    GenderType::class => [
        GenderType::Male => 'Uomo',
        GenderType::Female => 'Donna',
        GenderType::Other => 'Altro',
    ],

];
