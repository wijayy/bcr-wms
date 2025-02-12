<?php

namespace Database\Seeders;

use App\Models\Box;
use App\Models\BoxDetail;
use App\Models\ConvertionRate;
use App\Models\Goods;
use App\Models\Job;
use App\Models\Job_Detail;
use App\Models\Shipment;
use App\Models\Stock;
use App\Models\Supplier;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder {
    /**
     * Seed the application's database.
     */
    public function run(): void {
        for ($i = 0; $i < 5; $i++) {
            User::factory(1)->create([
                'email' => "user$i@admin.com"
            ]);
        }

        $shipment = Shipment::factory(20)->recycle([User::all()])->create();

        foreach ($shipment as $key => $item) {
            Job::factory(1)->recycle($item)->create(['no_job' => "$key"]);
        }

        // Job::factory(100)->recycle(Shipment::all())->create();
        Supplier::factory(count: 20)->recycle([Shipment::all()])->create();

        Goods::factory(100)->recycle([Supplier::all()])->create();

        // Stock::factory(500)->recycle(Goods::all())->create();

        // Box::factory(100)->recycle([Job::all()])->create();
        // BoxDetail::factory(300)->recycle([Box::all(), Goods::all()])->create();

        // Job_Detail::factory(500)->recycle([Job::all(), Goods::all()])->create();

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'admin@admin.com',
            "is_admin" => true
        ]);

        ConvertionRate::factory(1)->create();
    }
}