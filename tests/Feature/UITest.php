<?php

use App\Team;
use App\User;
use App\Session;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class UITest extends TestCase
{
    use DatabaseTransactions;

    protected $session;
    protected $user;
    protected $team;

    public function setUp()
    {
        parent::setUp();
        $this->session = factory(Session::class, 5)->create();
        $this->user = factory(User::class)->create();
        $this->team = factory(Team::class)->create();
        $this->user->teams()->attach($this->team, ['role' => 'owner']);
    }
    
    /** @test */
    public function itDisplaysTheDashboard()
    {
        $this->actingAs($this->user)
            ->get('/home')
//             ->assertSee('SK4')
            //->assertSee($this->session[0]->hostname)
            ->assertSee('ubuntu');
    }

    /** @test */
    public function itDisplaysTheSessionListPage()
    {
        $this->actingAs($this->user)
            ->get('/sessions')
            ->assertSee('ubuntu');
    }

    /** @test */
    public function itDisplaysTheSessionPage()
    {
        $this->actingAs($this->user)
            ->get('/sessions/'.$this->session[0]->sid)
            ->assertSee($this->session[0]->sid);
    }
}
