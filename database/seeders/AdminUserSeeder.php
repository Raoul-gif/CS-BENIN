<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run()
    {
        User::create([
            'name' => 'Admin Principal',
            'email' => 'admin@carnetsante.bj',
            'password' => Hash::make('admin123'),
            'telephone' => '+229 00 00 00 00',
            'langue_preferee' => 'fr',
            'role' => 'admin'
        ]);

        User::create([
            'name' => 'Admin Secondaire',
            'email' => 'admin2@carnetsante.bj',
            'password' => Hash::make('admin123'),
            'telephone' => '+229 00 00 00 01',
            'langue_preferee' => 'fr',
            'role' => 'admin'
        ]);
    }
}