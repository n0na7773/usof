<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Carbon\Carbon;

class PostSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \DB::table('posts')->insert([
            'user_id' => 1,
            'title' => Str::random(10),
            'rating' => 1,
            'content' => Str::random(10),
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
        ]);
        \DB::table('posts')->insert([
            'user_id' => 1,
            'title' => Str::random(10),
            'rating' => 2,
            'content' => Str::random(10),
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
        ]);
        \DB::table('posts')->insert([
            'user_id' => 2,
            'title' => Str::random(10),
            'rating' => -1,
            'content' => Str::random(10),
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
        ]);
        \DB::table('posts')->insert([
            'user_id' => 3,
            'title' => Str::random(10),
            'rating' => 0,
            'status' => 'inactive',
            'content' => Str::random(10),
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
        ]);
        \DB::table('posts')->insert([
            'user_id' => 4,
            'title' => Str::random(10),
            'rating' => 0,
            'content' => Str::random(10),
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
        ]);

        \DB::table('category_post')->insert([
            'post_id' => 1,
            'category_id' => 1,
        ]);
        \DB::table('category_post')->insert([
            'post_id' => 1,
            'category_id' => 2,
        ]);
        \DB::table('category_post')->insert([
            'post_id' => 1,
            'category_id' => 3,
        ]);
        \DB::table('category_post')->insert([
            'post_id' => 2,
            'category_id' => 1,
        ]);
        \DB::table('category_post')->insert([
            'post_id' => 3,
            'category_id' => 2,
        ]);
        \DB::table('category_post')->insert([
            'post_id' => 4,
            'category_id' => 5,
        ]);
        \DB::table('category_post')->insert([
            'post_id' => 4,
            'category_id' => 3,
        ]);
        \DB::table('category_post')->insert([
            'post_id' => 5,
            'category_id' => 4,
        ]);

        \DB::table('subscriptions')->insert([
            'post_id' => 1,
            'user_id' => 1,
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
        ]);
        \DB::table('subscriptions')->insert([
            'post_id' => 2,
            'user_id' => 1,
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
        ]);
        \DB::table('subscriptions')->insert([
            'post_id' => 3,
            'user_id' => 1,
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
        ]);
        \DB::table('subscriptions')->insert([
            'post_id' => 3,
            'user_id' => 2,
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
        ]);
        \DB::table('subscriptions')->insert([
            'post_id' => 4,
            'user_id' => 2,
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
        ]);

        \DB::table('favorites')->insert([
            'post_id' => 1,
            'user_id' => 1,
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
        ]);
        \DB::table('favorites')->insert([
            'post_id' => 1,
            'user_id' => 2,
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
        ]);
        \DB::table('favorites')->insert([
            'post_id' => 2,
            'user_id' => 1,
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
        ]);
        \DB::table('favorites')->insert([
            'post_id' => 3,
            'user_id' => 1,
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
        ]);
        \DB::table('favorites')->insert([
            'post_id' => 4,
            'user_id' => 3,
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
        ]);
    }
}
