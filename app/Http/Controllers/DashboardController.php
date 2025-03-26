<?php

namespace App\Http\Controllers;

use App\Enums\Permissions;
use App\Enums\RaceType;
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

        $races = Auth::user()->athlete ? (Auth::user()->athlete->fees()->whereHas('race', function($query){
            $query->type(RaceType::Race);
        })->get()) : collect([]);
        
        $races_to_pay = $races->filter(function($item){
            return !$item->athletefee->payed_at;
        });

        $races_payed = $races->filter(function($item){
            return $item->athletefee->payed_at;
        });
        
        $tracks = Auth::user()->athlete ? (Auth::user()->athlete->fees()->whereHas('race', function($query){
            $query->type(RaceType::Track);
        })->get()) : collect([]);

        $track_to_pay = $tracks->filter(function($item){
            return !$item->athletefee->payed_at;
        });

        $track_payed = $tracks->filter(function($item){
            return $item->athletefee->payed_at;
        });

        $certificate = Auth::user()->athlete->certificate ?? null;
        
        return view('backend.index', compact('races_payed', 'races_to_pay', 'track_to_pay', 'track_payed', 'certificate'));
    }

    public function certificates()
    {
        $this->authorize('viewAny', [Certificate::class, null]);

        if (request()->ajax()) {

            $builder = Athlete::active()->with(['certificate' => function($query){
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

    public function fees($raceType)
    {
        if($raceType == RaceType::Race){
            $this->authorize('registerPaymentRace', AthleteFee::class);
        }else if($raceType == RaceType::Track){
            $this->authorize('registerPaymentTrack', AthleteFee::class);
        }else{
            abort(401);
        }
        
        if (request()->ajax()) {

            $builder = Athlete::query()->whereHas('feesToPay.race', function($query) use($raceType){
                $query->type($raceType);
            })->with([
                'feesToPay' => function($query) use($raceType){
                    $query->whereHas('race', function($query) use($raceType){
                        $query->type($raceType);
                    });
                }, 
                'feesToPay.race', 
                'feesToPay.athletefee.voucher'
            ]);

            return datatables()->eloquent($builder)->filterColumn('name', function($query, $keyword) {
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
