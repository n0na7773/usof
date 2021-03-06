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
            'title' => "How to exit vim?",
            'rating' => 1,
            'content' => "Please help me to exit vim! I tried everthing...",
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
        ]);
        \DB::table('posts')->insert([
            'user_id' => 1,
            'title' => "Tips how to deploy a project",
            'rating' => 2,
            'content' => "Please give me some tips because I am going to type there anything I want to fill it up",
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
        ]);
        \DB::table('posts')->insert([
            'user_id' => 2,
            'title' => "Morgenshtern",
            'rating' => -1,
            'status' => 'inactive',
            'content' => "Pull up in the tank i ya edu v boy",
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
        ]);
        \DB::table('posts')->insert([
            'user_id' => 3,
            'title' => "Blah blah blah",
            'rating' => 0,
            'status' => 'inactive',
            'content' => "Blah blah blah blah blah blah blah blah blah blah blah blah. Blah blah blah.",
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
        ]);
        \DB::table('posts')->insert([
            'user_id' => 4,
            'title' => "Lorem Ipsum",
            'rating' => 0,
            'status' => 'inactive',
            'content' => "Lorem ipsum de cuatora in ceprimo lorem dino mi sentio labudeno.",
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
