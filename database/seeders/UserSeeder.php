<?php

namespace Database\Seeders;


use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'users' => 'Superadmin',
            'id_person' => 1,
            'status' => true,
            'last_login' => date('Y-m-d H:i:s'),
            'password_status' => false,
            'password' => bcrypt('sup3r4dm1n*2022'),
            'security_questions' => false
        ])->assignRole('Superadmin');
    }
}
