<?php

namespace App\Http\Controllers;

use App\Models\Template;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class TemplateController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if($request->query('type') == 'getTemplates') {
            return Template::with('user')->paginate(10);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if($request->query('type') == 'createTemplate') {
            if(!$request->hasFile('file')) {
                return response()->json([
                    'message' => 'No File Selected',
                    'status'  => 'error'
                ]);
            } else{
                if (!$request->file('file')->isValid()) {
                    return response()->json([
                        'message' => 'Invalid File Selected',
                        'status'  => 'error'
                    ]);
                } else{
                    $extension       = $request->file('file')->getClientOriginalExtension();
                    $fileUrl         = Str::random(20) . '.' . $extension;
                    $targetDirectory = 'public/templates';

                    try{
                        $template                = new Template;
                        $template->name          = $request->fileName;
                        $template->url           = $fileUrl;
                        $template->original_name = $request->file('file')->getClientOriginalName();
                        $template->uploaded_by   = auth()->id();
                        $template->save();
                    } catch (\Illuminate\Database\QueryException $e) {
                        return response()->json(['status' => 'error', 'message' => $e->getMessage()]);
                    }

                    if (!Storage::exists($targetDirectory)) Storage::makeDirectory($targetDirectory);
                    $request->file('file')->storeAs($targetDirectory, $fileUrl);

                    return response()->json([
                        'message' => 'Template added successfully',
                        'status'  => 'success'
                    ]);
                }
            }
        }
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
