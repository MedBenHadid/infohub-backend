<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = new User();
        $user->email ='admin.infohub@soprahr.com';
        $user->name = 'Admin';
        $user->password = 'Adm1n$S0pra!2024';
        $user->role_id = 1;
        $user->save();

        $user = new User();
        $user->email ='user.access@soprahr.com';
        $user->name = 'User';
        $user->password = 'User_Access#2024';
        $user->role_id = 2;

        $user->save();
    }
}
