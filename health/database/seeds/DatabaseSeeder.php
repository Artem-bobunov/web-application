<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(CreateAdminAccount::class);
        $this->call(CreateDoctorsGroup::class);
        $this->call(CreateUserGroup::class);
    }
}
