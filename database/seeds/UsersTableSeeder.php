<?php

use Illuminate\Database\Seeder;
use \Illuminate\Support\Facades\Hash;
use App\User;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'name' => 'Administrador',
            'email' => 'admin@playtechla.com',
            'password' => '123456'
        ]);

        User::create([
            'name' => 'Auditor',
            'email' => 'auditplay@playtechla.com',
            'password' => '123456'
        ]);
    }
}
