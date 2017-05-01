<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\User;
class MainTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */

     
    public function testMain()
    {
        $user = new User();
        $user->discord_id = "23943432";
        $user->nickname = "TEST USER";
        $user->token = "TOKEN TEST";
        $user->refreshToken = "REFRESH TOKEN TEST";
        $user->save();

        $response = $this->get('/join/overwatch');
        $response->assertStatus(302);

        $response = $this->get('/leave/overwatch');
        $response->assertStatus(302);

        $response = $this->get('/list/cah');
        $response->assertStatus(200);

        //AUTH

        $response = $this->actingAs($user, 'web')->get('/');
        $response->assertStatus(200);

        $response = $this->actingAs($user, 'web')->get('/join/overwatch');
        $response->assertStatus(200);

        $response = $this->actingAs($user, 'web')->get('/leave/overwatch');
        $response->assertStatus(200);

        $user->delete();
    }
}
