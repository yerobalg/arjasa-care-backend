<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;



class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('pengurus_apotek')->insert([
            'username' => "admin_arjasa",
            'fullname' => "Admin Arjasa",
            'password' => Hash::make(env('ADMIN_PASSWORD')),
            'is_karyawan' => false,
            'created_at' => 'CURRENT_TIMESTAMP',
            'updated_at' => 'CURRENT_TIMESTAMP',
        ]);
    }
}
