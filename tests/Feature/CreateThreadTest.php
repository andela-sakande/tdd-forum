<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class CreateThreadTest extends TestCase
{
    use DatabaseMigrations;

    protected $thread;

    protected function setUp()
    {
        parent::setUp();

        $this->thread = make('App\Thread');
    }

    /**
     * A basic test example.
     *
     * @return void
     */
    public function testAuthenticatedUserCanCreateThread()
    {
        $this->signIn();
        $response = $this->post('/threads', $this->thread->toArray());

        $this->get($response->headers->get('Location'))
            ->assertSee($this->thread->title)->assertSee($this->thread->body);
    }

    /**
     * A basic test example.
     *
     * @return void
     */
    public function testUnAuthenticatedGuestCantCreateThread()
    {
        $this->withExceptionHandling();

        $this->get('/threads/create')
            ->assertRedirect('/login');

        $this->post('/threads')
            ->assertRedirect('/login');
    }

    public function testThreadRequiresTitle()
    {
        $this->publishThread(['title' => null])
            ->assertSessionHasErrors('title');
    }

    public function testThreadRequiresBody()
    {
        $this->publishThread(['body' => null])
            ->assertSessionHasErrors('body');
    }

    private function publishThread($overRides = [])
    {
        $this->withExceptionHandling()->signIn();
        $thread = make('App\Thread', $overRides);

        return $this->post('/threads', $thread->toArray());
    }

    public function testThreadRequiresValidChannel()
    {
        factory('App\Channel', 2)->create();

        $this->publishThread(['channel_id' => null])
            ->assertSessionHasErrors('channel_id');

        $this->publishThread(['channel_id' => 999])
            ->assertSessionHasErrors('channel_id');
    }
}
