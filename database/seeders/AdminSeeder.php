<?php

namespace Database\Seeders;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;


class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
         User::firstOrCreate(
            ['email' => 'admin@covoiturage.bj'],
            [
                'first_name'        => 'Super',
                'last_name'         => 'Admin',
                'email'             => 'admin@covoiturage.bj',
                'phone'             => '00000000',
                'password'          => Hash::make('Admin@2026!'),
                'role'              => 'admin',
                'email_verified_at' => now(),
            ]
        );
    }
}
