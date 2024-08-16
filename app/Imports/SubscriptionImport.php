<?php

namespace App\Imports;

use App\Models\Athlete;
use App\Models\Race;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\ToCollection;

class SubscriptionImport implements ToCollection
{
    /**
    * @param Collection $collection
    */
    public function collection(Collection $collection)
    {
        $collection->each(function($item){
            $row_timestamp = $item[0];
            $row_race_name = $item[2];
            $row_athlete_name = $item[3];
            $row_fee_amount = $item[5];
            
            $timestamp = $row_timestamp ? Carbon::createFromFormat('d/m/Y H.i.s', $row_timestamp) : null;
            $athlete = Athlete::where(DB::raw("CONCAT(`surname`, ' ', `name`)"), 'like', $row_athlete_name)->firstOrFail();

            $race_name = (explode(' - ', trim($row_race_name)))[0];
            $fee = Race::where('name', 'like', $race_name)->firstOrFail()->fees()->firstOrFail();

            $data = [$athlete->id => [
                'custom_amount' => $row_fee_amount,
                'created_at' => $timestamp
            ]]; 
            $fee->athletes()->syncWithoutDetaching($data);
        });
    }
}
