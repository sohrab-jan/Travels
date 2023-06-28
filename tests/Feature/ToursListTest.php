<?php

namespace Tests\Feature;

use App\Models\Tour;
use App\Models\Travel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ToursListTest extends TestCase
{
    use RefreshDatabase;

    public function test_tours_list_by_travel_slug_correct_tours()
    {
        $travel = Travel::factory()->create();
        $tour = Tour::factory()->create(['travel_id' => $travel->id]);

        $response = $this->get(route('tours.index', $travel->slug));

        $response->assertStatus(200);
        $response->assertJsonCount(1, 'data');
        $response->assertJsonFragment(['id' => $tour->id]);
    }

    public function test_tour_price_is_shown_correctly()
    {
        $travel = Travel::factory()->create();
        Tour::factory()->create([
            'travel_id' => $travel->id,
            'price' => 123.45,
        ]);

        $response = $this->get(route('tours.index', $travel->slug));

        $response->assertStatus(200);
        $response->assertJsonCount(1, 'data');
        $response->assertJsonFragment(['price' => '123.45']);
    }

    public function test_tours_list_sorts_by_starting_date_correctly()
    {
        $travel = Travel::factory()->create();
        $laterTour = Tour::factory()->create([
            'travel_id' => $travel->id,
            'starting_date' => now()->addDays(2),
            'ending_date' => now()->addDays(3),
        ]);

        $earlierTour = Tour::factory()->create([
            'travel_id' => $travel->id,
            'starting_date' => now(),
            'ending_date' => now()->addDays(1),
        ]);

        $response = $this->get(route('tours.index', $travel->slug));

        $response->assertStatus(200);
        $response->assertJsonPath('data.0.id', $earlierTour->id);
        $response->assertJsonPath('data.1.id', $laterTour->id);
    }

    public function test_tours_list_sorts_by_price_correctly()
    {
        $travel = Travel::factory()->create();
        $expensiveTour = Tour::factory()->create([
            'travel_id' => $travel->id,
            'price' => 200,
        ]);

        $cheapLaterTour = Tour::factory()->create([
            'travel_id' => $travel->id,
            'price' => 100,
            'starting_date' => now()->addDays(2),
            'ending_date' => now()->addDays(3),
        ]);
        $cheapEarlierTour = Tour::factory()->create([
            'travel_id' => $travel->id,
            'price' => 100,
            'starting_date' => now(),
            'ending_date' => now()->addDays(1),
        ]);

        $params = [
            'travel' => $travel->slug,
            'sort_by' => 'price',
            'sort_order' => 'asc',
        ];
        $response = $this->get(route('tours.index', $params));

        $response->assertStatus(200);
        $response->assertJsonPath('data.0.id', $cheapEarlierTour->id);
        $response->assertJsonPath('data.1.id', $cheapLaterTour->id);
        $response->assertJsonPath('data.2.id', $expensiveTour->id);
    }

    public function test_tours_list_filters_by_price_correctly()
    {
        $travel = Travel::factory()->create();
        $expensiveTour = Tour::factory()->create([
            'travel_id' => $travel->id,
            'price' => 200,
        ]);
        $cheapTour = Tour::factory()->create([
            'travel_id' => $travel->id,
            'price' => 100,
        ]);
        $params = [
            'travel' => $travel->slug,
            'price_from' => 100,
        ];
        $response = $this->get(route('tours.index', $params));

        $response->assertJsonCount(2, 'data');
        $response->assertJsonFragment(['id' => $cheapTour->id]);
        $response->assertJsonFragment(['id' => $expensiveTour->id]);

        $params = [
            'travel' => $travel->slug,
            'price_from' => 150,
        ];
        $response = $this->get(route('tours.index', $params));

        $response->assertJsonCount(1, 'data');
        $response->assertJsonMissing(['id' => $cheapTour->id]);
        $response->assertJsonFragment(['id' => $expensiveTour->id]);

        $params = [
            'travel' => $travel->slug,
            'price_from' => 250,
        ];
        $response = $this->get(route('tours.index', $params));

        $response->assertJsonCount(0, 'data');

        $params = [
            'travel' => $travel->slug,
            'price_to' => 200,
        ];
        $response = $this->get(route('tours.index', $params));

        $response->assertJsonCount(2, 'data');
        $response->assertJsonFragment(['id' => $cheapTour->id]);
        $response->assertJsonFragment(['id' => $expensiveTour->id]);
    }

    public function test_tours_list_filters_by_starting_date_correctly()
    {
        $travel = Travel::factory()->create();
        $laterTour = Tour::factory()->create([
            'travel_id' => $travel->id,
            'starting_date' => now()->addDays(2),
            'ending_date' => now()->addDays(3),
        ]);
        $earlierTour = Tour::factory()->create([
            'travel_id' => $travel->id,
            'starting_date' => now(),
            'ending_date' => now()->addDays(1),
        ]);
        $params = [
            'travel' => $travel->slug,
            'price_from' => 100,
        ];
        $response = $this->get(route('tours.index', $params));
        $response->assertJsonCount(2, 'data');
        $response->assertJsonFragment(['id' => $earlierTour->id]);
        $response->assertJsonFragment(['id' => $laterTour->id]);
    }
}
