<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
class UsuariSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'email'         => 'admin',
            'password'      => 'admin',
            'nom'           => 'admin',
            'admin'         => '1',
            'validat'       => '1',
            'pswrdreset'    => '0',
        ]);
    }
}
