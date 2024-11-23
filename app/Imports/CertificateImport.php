<?php

namespace App\Imports;

use App\Models\Athlete;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use finfo;

class CertificateImport implements ToCollection, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function collection(Collection $rows)
    {
        
        $rows->each(function($row){
            $name = str_replace(' ', '', $row['nome']);
            if($name){
                $athlete = Athlete::whereRaw('REPLACE (CONCAT(surname,name)," ", "") like ?', ["%{$name}%"])->first();
                $expire_on = ($row['scadcertificato'] && intval($row['scadcertificato'])) ? Date::excelToDateTimeObject($row['scadcertificato']) : null;
                if($athlete && $expire_on){
                    $certificate = $athlete->certificate()->create([
                        'expires_on' => $expire_on,
                        'is_current' => true
                    ]);

                    $filename = $certificate->athlete_id . '.pdf';
                    $cert_file_tmp = 'certificates/' . $filename;
                    
                    if(Storage::exists($cert_file_tmp)){
                        $file_path = Storage::path($cert_file_tmp);
                        $finfo = new finfo(FILEINFO_MIME_TYPE);
                        
                        $file = new UploadedFile(
                            $file_path,
                            $filename,
                            $finfo->file($file_path),
                            filesize($file_path),
                            0,
                            false
                        );

                        $certificate->update([
                            'document' => $file
                        ]);
                    };
                    
                }
            }
        });
    }
}
