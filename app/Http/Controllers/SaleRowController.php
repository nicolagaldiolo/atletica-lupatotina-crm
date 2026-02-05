<?php

namespace App\Http\Controllers;

use App\Classes\Utility;
use App\Http\Requests\SaleRowRequest;
use App\Models\Order;
use App\Models\OrderRow;
use App\Models\Season;
use App\Models\User;

class SaleRowController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Season $season)
    {

    }

    public function edit(Season $season, Order $order, OrderRow $orderRow)
    {
        //$accountants = User::HandlePaymentsRace()->get();

        //return view('backend.seasons.orders.rows.edit', compact('season', 'order', 'orderRow', 'accountants'));
    }

    public function update(SaleRowRequest $request, Season $season, Order $order, OrderRow $orderRow)
    {
        //$orderRow->update($request->only('status'));

        //$payed = (bool) $request->get('payed');
        //$bank_transfer = (bool) $request->get('bank_transfer');
        
        //handleTransaction($orderRow, $payed, $orderRow->total_amount, $bank_transfer, $request->get('cashed_by'));
        
        //Utility::flashMessage();

        //return redirect(route('seasons.orders.orderRows.edit', [$season, $order, $orderRow]));
    }
}
