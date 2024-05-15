<?php

namespace App\Imports;

use App\Models\Athlete;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithMappedCells;
use Maatwebsite\Excel\Concerns\WithStartRow;
use PhpOffice\PhpSpreadsheet\Shared\Date;

class PaymentImport implements ToCollection, WithStartRow
{
    /**
     * @return int
     */
    public function startRow(): int
    {
        return 177;
    }

    /**
    * @param Collection $collection
    */
    public function collection(Collection $collection)
    {
        $collection->each(function($item){

            $date = intval($item[0]) ? Date::excelToDateTimeObject(intval($item[0]))->getTimestamp() : null;
            $payments = [];

            if($item[2] && $item[3]){
                $payments[] = [
                    'athlete' => $item[2],
                    'payment' => $item[3],
                ];
            }
            if($item[4] && $item[5]){
                $payments[] = [
                    'athlete' => $item[4],
                    'payment' => $item[5],
                ];
            }
            if($item[6] && $item[7]){
                $payments[] = [
                    'athlete' => $item[6],
                    'payment' => $item[7],
                ];
            }
            if($item[8] && $item[9]){
                $payments[] = [
                    'athlete' => $item[8],
                    'payment' => $item[9],
                ];
            }
            if($item[10] && $item[11]){
                $payments[] = [
                    'athlete' => $item[10],
                    'payment' => $item[11],
                ];
            }


            collect($payments)->each(function($item) use($date){
                //$athlete = Athlete::where(DB::raw("CONCAT(`surname`, ' ', `name`)"), 'like', $item['athlete'])->firstOrFail();
                //$athlete->races()->first()->athleterace->payments()->create([
                //    'amount' => $item['payment'],
                //    'payed_at' => $date
                //]);
            });
        });
    }
}
