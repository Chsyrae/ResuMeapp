<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user               = new User();
        $user->first_name   = 'System';
        $user->last_name    = 'Admin';
        $user->email        = 'admin@resume.app';
        $user->password     = Hash::make('Qwerty1.');
        $user->active       = 1;
        $user->save();
        $role               = Role::whereName('System Admin')->first();
        $user->assignRole($role->id);


        $user               = new User();
        $user->first_name   = 'Test';
        $user->last_name    = 'User';
        $user->email        = 'user@resume.app';
        $user->password     = Hash::make('Qwerty1.');
        $user->active       = 1;
        $user->save();
        $role               = Role::whereName('Casual User')->first();
        $user->assignRole($role->id);
    }
}
