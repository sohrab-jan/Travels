<?php

namespace Tests\Feature;

use App\Models\Role;
use App\Models\Travel;
use App\Models\User;
use Database\Seeders\RoleSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminTourControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_pulic_user_cannot_access_adding_tour()
    {
        $travel = Travel::factory()->create();
        $response = $this->postJson(route('tours.store', $travel));

        $response->assertStatus(401);
    }

    public function test_non_admin_user_cannot_access_adding_tour()
    {
        $this->seed(RoleSeeder::class);
        $travel = Travel::factory()->create();

        $user = User::factory()->create();
        $user->roles()->attach(Role::where('name', 'Editor')->value('id'));

        $response = $this->actingAs($user)->postJson(route('tours.store', $travel));

        $response->assertStatus(403);
    }

    public function test_saves_tour_successfully_with_valid_data()
    {
        $this->seed(RoleSeeder::class);
        $user = User::factory()->create();
        $travel = Travel::factory()->create();

        $user->roles()->attach(Role::where('name', 'Admin')->value('id'));

        $response = $this->actingAs($user)->postJson(route('tours.store', $travel), [
            'name' => 'Tour name',
        ]);

        $response->assertStatus(422);

        $response = $this->actingAs($user)->postJson(route('tours.store', $travel), [
            'name' => 'Tour name',
            'starting_date' => '2002/10/10',
            'ending_date' => '2022/10/10',
            'price' => 5,
        ]);

        $response->assertStatus(201);
        $response = $this->get(route('tours.index', $travel->slug));
        $response->assertJsonFragment(['name' => 'Tour name']);
    }
}
