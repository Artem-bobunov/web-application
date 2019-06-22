<?php

use App\Role;
use Illuminate\Database\Seeder;

class CreateDoctorsGroup extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $doctor = new Role();
        $doctor->name         = 'doctor';
        $doctor->display_name = 'Доктор';
        $doctor->description  = 'Доктор';
        $doctor->save();
    }
}
