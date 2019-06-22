<?php

use App\Role;
use App\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class CreateAdminAccount extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $pass = "wGBufhlpl0";
        $user = User::create([
            'name' => "Admin",
            'email' => "admin@site.ru",
            'password' => Hash::make($pass)
        ]);

        $admin = new Role();
        $admin->name         = 'admin';
        $admin->display_name = 'Администратор';
        $admin->description  = 'Администратор';
        $admin->save();

        $user->attachRole($admin);
    }
}
