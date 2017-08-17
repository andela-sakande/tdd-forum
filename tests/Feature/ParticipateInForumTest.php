<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ParticipateInForumTest extends TestCase
{
    use DatabaseMigrations;

    protected $thread;

    protected $reply;

    protected  $user;

    protected function setUp()
    {
        parent::setUp();
        $this->thread = factory('App\Thread')->create();
        $this->reply = factory('App\Reply')->create();
        $this->user = factory('App\User')->create();
    }

    public function testUnAuthenticatedUsersCantReply()
    {
        $this->expectException('Illuminate\Auth\AuthenticationException');
        $this->post($this->thread->path() . '/replies', []);
    }

    /**
     * A basic test example.
     *
     * @return void
     */
    public function testAnAuthenticatedUserMayParticipateInForum()
    {
        $this->be($this->user);

        $this->post($this->thread->path() . '/replies', $this->reply->toArray());

        $this->get($this->thread->path())
            ->assertSee($this->reply->body);
    }
}
