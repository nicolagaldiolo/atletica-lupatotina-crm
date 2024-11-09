<?php

namespace App\Http\Controllers;

use App\Enums\Permissions;
use App\Http\Controllers\Controller;
use App\Models\Athlete;
use App\Models\AthleteFee;
use App\Models\Certificate;
use Illuminate\Support\Facades\Auth;
use Kirschbaum\PowerJoins\PowerJoinClause;

class DashboardController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function index()
    {
        $this->authorize(Permissions::ViewDashboard);

        $races_to_pay = (Auth::user()->athlete->fees ?? collect([]))->filter(function($item){
            return !$item->athletefee->payed_at;
        });

        $races_payed = (Auth::user()->athlete->fees ?? collect([]))->filter(function($item){
            return $item->athletefee->payed_at;
        });

        $certificate = Auth::user()->athlete->certificate ?? null;
        
        return view('backend.index', compact('races_payed', 'races_to_pay', 'certificate'));
    }

    public function certificates()
    {
        $this->authorize('viewAny', [Certificate::class, null]);

        if (request()->ajax()) {

            $builder = Athlete::with(['certificate' => function($query){
                $query->expiring();
            }])->leftJoinRelationship('certificate', function(PowerJoinClause $join){
                $join->expiring();
            })->whereHas('certificate', function($query){
                $query->expiring();
            });

            return datatables()->eloquent($builder)
                ->filterColumn('name', function($query, $keyword) {
                    $sql = "CONCAT(name, surname)  like ?";
                    $query->whereRaw($sql, ["%{$keyword}%"]);
                })
                ->orderColumn('name', function ($query, $order) {
                    $query->orderBy('surname', $order)->orderBy('name', $order);
                })
                ->editColumn('name', function ($data) {
                    return $data->fullname;
                })
                ->orderColumn('certificate', function ($query, $order) {
                    $query->orderBy('certificates.expires_on', $order);
                })->make(true);
        }
    }

    public function fees()
    {
        $this->authorize('registerPayment', AthleteFee::class);
        
        if (request()->ajax()) {
            return datatables()->eloquent(Athlete::query()->whereHas('feesToPay')->with(['feesToPay.race', 'feesToPay.athletefee.voucher']))
                ->filterColumn('name', function($query, $keyword) {
                    $sql = "CONCAT(name, surname)  like ?";
                    $query->whereRaw($sql, ["%{$keyword}%"]);
                })
                ->orderColumn('name', function ($query, $order) {
                    $query->orderBy('surname', $order)->orderBy('name', $order);
                })
                ->make(true);
        }
    }
}
