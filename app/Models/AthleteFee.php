<?php

namespace App\Models;

use App\Enums\VoucherType;
use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AthleteFee extends Pivot
{
    use HasFactory;

    protected $fillable = [
        'payed_at',
        'custom_amount',
        'voucher_id'
    ];

    protected $casts = [
        'payed_at' => 'datetime',
        'custom_amount' => 'float'
    ];

    protected static function booted(): void
    {
        static::creating(function ($model) {
            if($model->voucher_id){
                $voucher = Voucher::findOrFail($model->voucher_id);
                $diff_to_pay = $model->custom_amount - $voucher->amount_calculated;
                
                // Nel caso in cui utilizzando il vouchers sono comunque a credito
                // Aggiorno il voucher corrente assegnandogli come valore l'importo della quota corrente
                // e creo un nuovo voucher per un utilizzo futuro dell'importo rimanente
                
                if($diff_to_pay < 0){
                    $voucher->update([
                        'amount' => $model->custom_amount
                    ]);

                    $model->athlete->vouchers()->create([
                        'name' => $voucher->name . '-1',
                        'type' => VoucherType::Credit,
                        'amount' => abs($diff_to_pay)
                    ]);
                    
                }

                $model->custom_amount = ($model->custom_amount - $voucher->amount_calculated);
            }
        });
    }

    public function fee(): BelongsTo
    {
        return $this->belongsTo(Fee::class);
    }

    public function athlete(): BelongsTo
    {
        return $this->belongsTo(Athlete::class);
    }

    public function voucher(): BelongsTo
    {
        return $this->belongsTo(Voucher::class);
    }
}
