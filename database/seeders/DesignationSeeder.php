<?php

namespace Database\Seeders;

use App\Models\Designation;
use Illuminate\Database\Seeder;

class DesignationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Designation::truncate();
        Designation::create(
            [
                'name' => 'Demo Designation',
                'created_admin_id' => 1,
            ]
        );
    }
}
