<?php

use App\Role;
use Illuminate\Database\Seeder;

class CreateUserGroup extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = new Role();
        $user->name         = 'user';
        $user->display_name = 'Пользователь';
        $user->description  = 'Пользователь';
        $user->save();
    }
}
