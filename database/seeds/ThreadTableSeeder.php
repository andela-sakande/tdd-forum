<?php

use Illuminate\Database\Seeder;

class ThreadTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(App\Thread::class, 50)->create()->each(function ($u) {
            factory(App\Reply::class, 10)->create(['thread_id' => $u->id]);
        });
    }
}
