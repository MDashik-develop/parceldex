<?php

namespace Database\Seeders;

use App\Models\DeliveryService;
use Illuminate\Database\Seeder;

class DeliveryServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DeliveryService::truncate();
        DeliveryService::create(
            [
                'name'              => 'Example Name',
                'name'              => str_slug('Example Name'),
                'short_details'     => 'Example Short Details',
                'long_details'      => 'Example Long Details',
                'image'             => '15963474337DKfqm2d6hRtB3Y97522.jpg',
                'created_admin_id'  => 1,
            ]
        );
    }
}
