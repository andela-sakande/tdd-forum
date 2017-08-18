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
        $this->thread = create('App\Thread');
        $this->reply = create('App\Reply');
        $this->user = create('App\User');
    }

    public function testUnAuthenticatedUsersCantReply()
    {
        $this->withExceptionHandling();
        $this->post($this->thread->path() . '/replies', $this->reply->toArray());
    }

    /**
     * A basic test example.
     *
     * @return void
     */
    public function testAnAuthenticatedUserMayParticipateInForum()
    {
        $this->signIn();

        $this->post($this->thread->path() . '/replies', $this->reply->toArray());

        $this->get($this->thread->path())
            ->assertSee($this->reply->body);
    }
}
