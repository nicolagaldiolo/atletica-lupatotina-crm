<?php

namespace App\Http\Controllers;

use App\Models\Athlete;
use App\Classes\Utility;
use App\Models\Certificate;
use App\Http\Controllers\Controller;
use App\Http\Requests\AthleteCertificatesRequest;

class CertificateController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Athlete $athlete)
    {
        $this->authorize('viewAny', [Certificate::class, $athlete]);
        
        if (request()->ajax()) {
            return datatables()->eloquent($athlete->certificates())
            ->addColumn('action', function ($certificate) use($athlete){
                return view('backend.athletes.certificates.partials.action_column', compact('athlete', 'certificate'));
            })
            ->make(true);
        }else{
            return view('backend.athletes.certificates.index', compact('athlete'));
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Athlete $athlete)
    {
        $this->authorize('create', [Certificate::class, $athlete]);

        $certificate = new Certificate();
        $certificate->is_current = 1;
        return view('backend.athletes.certificates.create', compact('athlete', 'certificate'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(AthleteCertificatesRequest $request, Athlete $athlete)
    {
        $this->authorize('create', [Certificate::class, $athlete]);
        
        $certificate = $athlete->certificate()->create($request->except('document'));
        $certificate->document = $request->file('document', null);
        $certificate->save();
        
        Utility::flashMessage();
        
        return redirect(route('athletes.certificates.index', $athlete));
    }

    /**
     * Display the specified resource.
     */
    public function show(Certificate $certificate)
    {
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Athlete $athlete, Certificate $certificate)
    {
        $this->authorize('update', $certificate);

        return view('backend.athletes.certificates.edit', compact('athlete', 'certificate'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(AthleteCertificatesRequest $request, Athlete $athlete, Certificate $certificate)
    {
        $this->authorize('update', $certificate);

        $certificate->update($request->validated());
        Utility::flashMessage();
        return redirect(route('athletes.certificates.index', $athlete));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Athlete $athlete, Certificate $certificate)
    {
        $this->authorize('delete', $certificate);

        $certificate->delete();
        Utility::flashMessage();
        return redirect(route('athletes.certificates.index', $athlete));
    }
}
