<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class RaceSubscriptionsExport implements FromView
{
    private $subscriptions;

    public function __construct($subscriptions)
    {
        $this->subscriptions = $subscriptions;
    }

    public function view(): View
    {
        return view('backend.races.export.subscriptions', [ 
            'subscriptions' => $this->subscriptions
        ]);
    }
}
