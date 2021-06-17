<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Carbon\Carbon;

class CommentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \DB::table('comments')->insert([
            'user_id' => 1,
            'post_id' => 2,
            'content' => "Just google it",
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
        ]);
        \DB::table('comments')->insert([
            'user_id' => 2,
            'post_id' => 1,
            'rating' => 1,
            'content' => "hahahaha",
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
        ]);
        \DB::table('comments')->insert([
            'user_id' => 3,
            'post_id' => 1,
            'rating' => -1,
            'content' => "stupid post",
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
        ]);
        \DB::table('comments')->insert([
            'user_id' => 1,
            'post_id' => 2,
            'content' => "same dude",
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
        ]);
        \DB::table('comments')->insert([
            'user_id' => 1,
            'post_id' => 3,
            'content' => "i don't know but I think someone can help you",
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
        ]);
    }
}
