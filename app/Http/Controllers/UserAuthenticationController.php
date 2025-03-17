<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Notifications\OtpNotification;
use App\Notifications\SignupNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserAuthenticationController extends Controller
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
        if($request->query('type') == 'signUp') {
            $request->validate([
                'first_name' => 'required',
                'last_name'  => 'required',
                'email'      => 'required',
                'password'   => 'required'
            ]);
    
            $user = User::whereEmail($request->email)->first();
            if (is_null($user)) {
                try {
                    $user = new User;
                    $user->first_name = $request->first_name;
                    $user->last_name  = $request->last_name;
                    $user->email      = $request->email;
                    $user->password   = Hash::make($request->password);
                    $user->save();
                } catch (\Illuminate\Database\QueryException $e) {
                    return response()->json(['status' => 'error', 'message' => $e->getMessage()]);
                }
    
                //$user->notify(new SignupNotification($user));
                return response()->json([
                    'status'  => 'success',
                    'message' => 'Account created successfully.'
                ]);
            } else {
                return response()->json([
                    'status'  => 'warning',
                    'message' => 'Account exists. Login instead.'
                ]);
            }
        } elseif($request->query('type') == 'login') {
            $credentials = $request->validate([
                'email'    => 'required',
                'password' => 'required',
            ]);

            if(Auth::attempt($credentials)) {
                $user = User::whereEmail($request->email)->first();
                if($user->active == 0) {
                    try {
                        $user->active = 1;
                        $user->save();
                    } catch (\Illuminate\Database\QueryException $e) {
                        return response()->json(['status' => 'error', 'message' => $e->getMessage()]);
                    }
                }
                if(!collect(['admin@resume.app', 'user@resume.app'])->contains($user->email)) {
                    $user->notify(new OtpNotification($user, Str::random(6)));
                }
                return response()->json([
                    'status'  => 'success',  
                    'message' => 'Login successful',
                    'token'   => $user->createToken('Personal Access Token',['user'])->plainTextToken
                ]);
            } else {
                return response()->json([
                    'status'  => 'error',
                    'message' => 'Invalid credentials'
                ]);
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
