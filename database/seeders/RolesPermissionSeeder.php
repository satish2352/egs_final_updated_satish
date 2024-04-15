<?php

namespace Database\Seeders;

use App\Models\RolesPermissions;
use Illuminate\Database\Seeder;

class RolesPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        RolesPermissions::create(
            [
                'permission_id' =>1,
                'role_id' => 1,
                'per_list' => true,
                'per_add' => true,
                'per_edit' => false,
                'per_update' => false,
                'per_delete' => true,
                'created_at' => \Carbon\Carbon::now(),
                'updated_at' => \Carbon\Carbon::now()
            ]);
    
            RolesPermissions::create(
            [
                'permission_id' =>2,
                'role_id' => 1,
                'per_list' => true,
                'per_add' => false,
                'per_edit' => false,
                'per_update' => true,
                'per_delete' => false,
                'created_at' => \Carbon\Carbon::now(),
                'updated_at' => \Carbon\Carbon::now()
            ]);

       
    }
}
