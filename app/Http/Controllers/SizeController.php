<?php

namespace App\Http\Controllers;

use App\Classes\Utility;
use App\Http\Requests\SizeRequest;
use App\Models\Size;

class SizeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $this->authorize('viewAny', Size::class);

        if (request()->ajax()) {

            $builder = Size::query();

            return datatables()->eloquent($builder)
                ->addColumn('action', function ($size) {
                    return view('backend.sizes.partials.action_column', compact('size'));
                })->make(true);
        }else{
            return view('backend.sizes.index');
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->authorize('create', Size::class);

        $size = new Size();
        $size->is_active = true;
        
        return view('backend.sizes.create', compact('size'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(SizeRequest $request)
    {
        $this->authorize('create', Size::class);

        Size::create($request->validated());

        Utility::flashMessage();
        
        return redirect(route('sizes.index'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Size $size)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Size $size)
    {
        $this->authorize('update', $size);

        return view('backend.sizes.edit', compact('size'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(SizeRequest $request, Size $size)
    {
        $this->authorize('update', $size);
        $size->update($request->validated());

        Utility::flashMessage();
        return redirect(route('sizes.index'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Size $size)
    {
        $this->authorize('delete', $size);
        $size->delete();
        Utility::flashMessage();
        return redirect(route('sizes.index'));
    }
}
