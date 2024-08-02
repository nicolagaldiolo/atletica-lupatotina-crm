<?php

namespace App\Imports;

use App\Models\Athlete;
use App\Models\Race;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class SubscriptionImport implements ToCollection, WithStartRow, WithHeadingRow
{
    /**
     * @return int
     */
    public function startRow(): int
    {
        return 2;
    }

    /**
    * @param Collection $collection
    */
    public function collection(Collection $collection)
    {
        $collection->each(function($item){
            $athletes = collect(explode(', ', $item['atleta']))->unique()->reduce(function($arr, $item){
                $athlete = Athlete::where(DB::raw("CONCAT(`surname`, ' ', `name`)"), 'like', $item)->firstOrFail();
                $arr[] = $athlete;
                return $arr;
            }, []);

            $race_name = (explode(' - ', trim($item['gara'])))[0];
            $fee = Race::where('name', 'like', $race_name)->firstOrFail()->fees()->firstOrFail();
            
            $date = $item['timestamp'] ? Date::excelToDateTimeObject($item['timestamp'])->getTimestamp() : null;
            $athletes = collect($athletes)->mapWithKeys(function($item) use($date){
                return [$item->id => ['created_at' => $date]];
            })->toArray();

            $fee->athletes()->syncWithoutDetaching($athletes);
        });
    }
}
