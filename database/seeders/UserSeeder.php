<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Carbon\Carbon;

class UserSeeder extends Seeder
{
    public function run()
    {
        \DB::table('users')->insert([
            'login' => "peepo",
            'full_name' => "Peepo Bruh",
            'email' => "razilur@gmail.com",
            'password' => Hash::make('peepo'),
            'role' => "admin",
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
        ]);
        \DB::table('users')->insert([
            'login' => "pepe_ga",
            'full_name' => "Pepega",
            'email' => "pepe_ga@gmail.com",
            'password' => Hash::make('peepoga'),
            'role' => "user",
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
        ]);
        \DB::table('users')->insert([
            'login' => "clown",
            'full_name' => "Pepe Clown",
            'email' => "pepenotclown@gmail.com",
            'password' => Hash::make('notclown'),
            'role' => "user",
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
        ]);
        \DB::table('users')->insert([
            'login' => "pog",
            'full_name' => "Pog Champ",
            'email' => "pogchamp@gmail.com",
            'password' => Hash::make('pooog'),
            'role' => "user",
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
        ]);
        \DB::table('users')->insert([
            'login' => "zeka",
            'full_name' => "Zeka Rulet",
            'email' => "zeka@gmail.com",
            'password' => Hash::make('zekakrut'),
            'role' => "user",
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
        ]);
    }
}
