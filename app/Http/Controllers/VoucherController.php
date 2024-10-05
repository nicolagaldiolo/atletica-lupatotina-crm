<?php

namespace App\Http\Controllers;

use App\Classes\Utility;
use App\Http\Requests\VoucherRequest;
use App\Models\Athlete;
use App\Models\Voucher;
use Illuminate\Http\Request;

class VoucherController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Athlete $athlete)
    {

        $this->authorize('viewAny', Voucher::class);

        if (request()->ajax()) {
            return datatables()->eloquent($athlete->vouchers())
            ->addColumn('action', function ($voucher) use($athlete){
                return view('backend.athletes.vouchers.partials.action_column', compact('athlete', 'voucher'));
            })->make(true);
        }else{
            return view('backend.athletes.vouchers.index', compact('athlete'));
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Athlete $athlete)
    {
        $this->authorize('create', Voucher::class);

        $voucher = new Voucher();
        return view('backend.athletes.vouchers.create', compact('athlete', 'voucher'));
    }
    

    /**
     * Store a newly created resource in storage.
     */
    public function store(VoucherRequest $request, Athlete $athlete)
    {
        $this->authorize('create', Voucher::class);

        $athlete->vouchers()->create($request->validated());
        Utility::flashMessage();
        
        return redirect(route('athletes.vouchers.index', $athlete));
    }

    /**
     * Display the specified resource.
     */
    public function show(Voucher $voucher)
    {
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Athlete $athlete, Voucher $voucher)
    {
        $this->authorize('update', $voucher);

        return view('backend.athletes.vouchers.edit', compact('athlete', 'voucher'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(VoucherRequest $request, Athlete $athlete, Voucher $voucher)
    {
        $this->authorize('update', $voucher);

        $voucher->update($request->validated());
        Utility::flashMessage();
        return redirect(route('athletes.vouchers.index', $athlete));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Athlete $athlete, Voucher $voucher)
    {
        $this->authorize('delete', $voucher);
        
        $voucher->delete();
        Utility::flashMessage();
        return redirect(route('athletes.vouchers.index', $athlete));
    }
}
