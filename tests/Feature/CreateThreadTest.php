<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class CreateThreadTest extends TestCase
{
    use DatabaseMigrations;

    protected $user;

    protected $thread;

    protected function setUp()
    {
        parent::setUp();
        $this->user = factory('App\User')->create();
        $this->thread = factory('App\Thread')->create();
    }

    /**
     * A basic test example.
     *
     * @return void
     */
    public function testAuthenticatedUserCanCreateThread()
    {
        $this->actingAs($this->user);
        $this->post('/threads', $this->thread->toArray());

        $this->get($this->thread->path())
            ->assertSee($this->thread->title)->assertSee($this->thread->body);
    }

    /**
     * A basic test example.
     *
     * @return void
     */
    public function testAuthenticatedGuestCantCreateThread()
    {
        $this->expectException('Illuminate\Auth\AuthenticationException');
        $this->post('/threads', []);

//        $this->get($this->thread->path())
//            ->assertSee($this->thread->title)->assertSee($this->thread->body);
    }
}
