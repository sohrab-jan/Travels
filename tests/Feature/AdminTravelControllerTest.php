<?php

namespace Tests\Feature;

use App\Models\Role;
use App\Models\User;
use Database\Seeders\RoleSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminTravelControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_pulic_user_cannot_access_adding_travel()
    {
        $response = $this->postJson(route('travels.store'));

        $response->assertStatus(401);
    }

    public function test_non_admin_user_cannot_access_adding_travel()
    {
        $this->seed(RoleSeeder::class);
        $user = User::factory()->create();
        $user->roles()->attach(Role::where('name', 'Editor')->value('id'));

        $response = $this->actingAs($user)->postJson(route('travels.store'));

        $response->assertStatus(403);
    }

    public function test_saves_travel_successfully_with_valid_data()
    {
        $this->seed(RoleSeeder::class);
        $user = User::factory()->create();
        $user->roles()->attach(Role::where('name', 'Admin')->value('id'));

        $response = $this->actingAs($user)->postJson(route('travels.store'), [
            'name' => 'Travel name',
        ]);

        $response->assertStatus(422);

        $response = $this->actingAs($user)->postJson(route('travels.store'), [
            'name' => 'Travel name',
            'is_public' => 1,
            'description' => 'some description',
            'number_of_days' => 5,
        ]);

        $response->assertStatus(201);
        $response = $this->get(route('travels.index'));
        $response->assertJsonFragment(['name' => 'Travel name']);
    }
}
