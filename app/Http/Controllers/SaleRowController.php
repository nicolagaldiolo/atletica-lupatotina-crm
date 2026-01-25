<?php

namespace App\Http\Controllers;

use App\Classes\Utility;
use App\Enums\ArticleType;
use App\Http\Requests\ArticleRequest;
use App\Http\Requests\OrderRequest;
use App\Models\Article;
use App\Models\Athlete;
use App\Models\Order;
use App\Models\OrderRow;
use App\Models\Season;
use App\Models\User;
use Illuminate\Support\Facades\DB;

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
        $accountants = User::HandlePaymentsRace()->get();

        return view('backend.seasons.orders.rows.edit', compact('season', 'order', 'orderRow', 'accountants'));
    }
}
