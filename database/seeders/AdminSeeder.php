<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder {
   public function run() 
   {
        DB::statement("INSERT INTO roles (name, created_at, updated_at) VALUES ('Admin', CURRENT_TIMESTAMP, CURRENT_TIMESTAMP), ('User', CURRENT_TIMESTAMP, CURRENT_TIMESTAMP)");

        
        $now = date('Y-m-d H:i:s');
        $email = 'admin@gmail.com';
        $passwordHash = Hash::make('admin');

        $roleId = DB::table('roles')->where('name','Admin')->value('id');

        DB::statement("INSERT INTO users (name, email, password,slug,role_id, status,created_at, updated_at) VALUES ('Admin', '{$email}', '{$passwordHash}','admin', {$roleId},1,'{$now}', '{$now}')");

    }
}