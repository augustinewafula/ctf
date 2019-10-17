<?php

use Illuminate\Database\Seeder;

class AwardPointsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('award_points')->insert([
            'flag' => 'flag{thid_is_the_password}',
            'points' => '10',
            'created_at' =>now(),
            'updated_at' =>now(),
        ]);
    }
}
