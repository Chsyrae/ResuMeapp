<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ResumeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        \Log::info($request->all());

        $personalInfo         = $request['personalInfo'];
        $workExperiences      = $request['workExperience'];
        $educationExperiences = $request['educationExperience'];
        $skills               = $request['skills'];
        $interests            = $request['interests'];

        $pdf                  = \Pdf::loadView('templates.emily', compact('personalInfo', 'workExperiences', 'educationExperiences', 'skills', 'interests'));
        $fileName             = $personalInfo['fname'] . ' ' . $personalInfo['otherNames'];
        return response($pdf->stream(), 200, [
            'Content-Disposition' => "attachment; filename={$fileName}"
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
