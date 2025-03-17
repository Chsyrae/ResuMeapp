<?php

namespace App\Http\Controllers;

use App\Models\UserDetails;
use Illuminate\Http\Request;

class UserDetailsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view ('user_details.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {  
        $request->validate([
                    'full_name' => 'required|string|max:255',
                    'email' => 'required|email|max:255|unique:user_details,email',
                    'phone_number' => 'required|numeric|unique:user_details',
                    'address' => 'nullable|string|max:255',
                ]);
        

        $detail = new UserDetails();

        $detail->user_id = auth()->id();
        $detail->full_name = $request->input('full_name');
        $detail->email = $request->input('email');
        $detail->phone_number = $request->input('phone_number');
        $detail->address = $request->input('address');
        $detail->user_id = auth()->id();
        $detail->save();
    //     $request->validate([
    //         'full_name' => 'required|string|max:255',
    //         'email' => 'required|email|max:255|unique:user_details,email',
    //         'phone_number' => 'required|numeric|unique:user_details',
    //         'address' => 'nullable|string|max:255',
    //     ]);

    //    // dd($request->all());

    //     UserDetails::create($request->except('_token'));

    //     return back()->with('success', 'User detail created successfully.');
    }
    /**
     * Display the specified resource.
     */
    public function show(UserDetails $userDetails)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(UserDetails $userDetails)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, UserDetails $userDetails)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(UserDetails $userDetails)
    {
        //
    }
}
