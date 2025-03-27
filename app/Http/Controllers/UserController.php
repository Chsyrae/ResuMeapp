<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if($request->query('type') == 'getUsers') {
            $users = User::with('roles')->withTrashed()->paginate(10);
            return $users;
        } elseif($request->query('type') == 'getRoles') {
            $roles = Role::get();
            return $roles;
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if($request->query('type') == 'createUser') {
            $request->validate([
                'fname'  => 'required',
                'lname'  => 'required',
                'email'  => 'required',
                'roleId' => 'required'
            ]);

            $userCheck = User::whereEmail($request->email)->first();
            if (auth()->user()->hasPermissionTo('user_create')) {
                if (is_null($userCheck)) {
                    try {
                        $user             = new User;
                        $user->first_name = $request->fname;
                        $user->last_name  = $request->lname;
                        $user->email      = $request->email;
                        $user->password   = Hash::make($request->password);
                        $user->active     = 1;
                        $user->save();
                    } catch (\Illuminate\Database\QueryException $e) {
                        return response()->json(['status' => 'error', 'message' => $e->getMessage()]);
                    }
                    return response()->json([
                        'message' => 'User created successfully',
                        'status'  => 'success'
                    ]);
                } else{
                    return response()->json([
                        'message' => 'User already exists',
                        'status'  => 'error'
                    ]);
                }
            } else{
                return response()->json([
                    'message' => 'Unauthorized operation',
                    'status'  => 'error'
                ]);
            }
        } elseif($request->query('type') == 'editUser') {
            $request->validate([
                'fname'  => 'required',
                'lname'  => 'required',
                'email'  => 'required',
                'roleId' => 'required'
            ]);

            $userCheck = User::whereEmail($request->email)->first();
            if (auth()->user()->hasPermissionTo('user_edit')) {
                if (!is_null($userCheck)) {
                    try {
                        $userCheck->first_name = $request->fname;
                        $userCheck->last_name  = $request->lname;
                        $userCheck->email      = $request->email;
                        $userCheck->password   = Hash::make($request->password);
                        $userCheck->assignRole($request->roleId);
                        $userCheck->save();
                    } catch (\Illuminate\Database\QueryException $e) {
                        return response()->json(['status' => 'error', 'message' => $e->getMessage()]);
                    }
                    return response()->json([
                        'message' => 'User updated successfully',
                        'status'  => 'success'
                    ]);
                } else{
                    return response()->json([
                        'message' => 'User not found',
                        'status'  => 'error'
                    ]);
                }
            } else{
                return response()->json([
                    'message' => 'Unauthorized operation',
                    'status'  => 'error'
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
        $userCheck = User::find(auth()->id());
        if ($userCheck->hasPermissionTo('user_archive')) {
            $user = User::find($id);
            if(!is_null($user)){
                try {
                    $user->delete();
                } catch (\Illuminate\Database\QueryException $e) {
                    return response()->json(['status' => 'error', 'message' => $e->getMessage()]);
                }
                return response()->json([
                    'message' => 'User deleted successfully',
                    'status'  => 'success'
                ]);
            } else{
                return response()->json([
                    'message' => 'User not found',
                    'status'  => 'error'
                ]);
            }
        } else{
            return response()->json([
                'message' => 'Unauthorized operation',
                'status'  => 'error'
            ]);
        }
    }
}
