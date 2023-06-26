<?php

namespace Tests\Feature;

use App\Models\Tours;
use App\Models\Travel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class TourListTest extends TestCase
{
    use RefreshDatabase;

    public function test_tours_list_by_travel_slug_returns_correct_tours(): void
    {
        $travel = Travel::factory()->create();
        $tour = Tours::factory()->create(['travel_id' => $travel->id]);

        $response = $this->get('/api/v1/travels/' . $travel->slug . '/tours');

        $response->assertStatus(200);
        $response->assertJsonCount(1, 'data');
        $response->assertJsonFragment(['id' => $tour->id]);
    }

    public function test_tour_price_is_shown_correctly(): void
    {
        $travel = Travel::factory()->create();
        Tours::factory()->create([
            'travel_id' => $travel->id,
            'price' => 123.45
        ]);

        $response = $this->get('/api/v1/travels/' . $travel->slug . '/tours');

        $response->assertStatus(200);
        $response->assertJsonCount(1, 'data');
        $response->assertJsonFragment(['price' => '123.45']);
    }

    public function test_tours_list_returns_pagination(): void
    {
        $toursPerPage = 15; // default laravel pagination count
        $travel = Travel::factory()->create();
        Tours::factory($toursPerPage + 1)->create(['travel_id' => $travel->id]);

        $response = $this->get('/api/v1/travels/' . $travel->slug . '/tours');

        $response->assertStatus(200);
        $response->assertJsonCount($toursPerPage, 'data');
        $response->assertJsonPath('meta.last_page', 2);
    }

    public function test_tours_list_sorts_by_start_date_correctly(): void
    {
        $travel = Travel::factory()->create();
        $laterTour = Tours::factory()->create([
            'travel_id' => $travel->id,
            'start_date' => now()->addDays(2),
            'end_date' => now()->addDays(3)
        ]);
        $earlierTour = Tours::factory()->create([
            'travel_id' => $travel->id,
            'start_date' => now(),
            'end_date' => now()->addDay()
        ]);

        $response = $this->get('/api/v1/travels/' . $travel->slug . '/tours');

        $response->assertStatus(200);
        $response->assertJsonPath('data.0.id', $earlierTour->id);
        $response->assertJsonPath('data.1.id', $laterTour->id);
    }
    public function test_tours_list_sorts_by_price_correctly(): void
    {
        $travel = Travel::factory()->create();
        $expensiveTour = Tours::factory()->create([
            'travel_id' => $travel->id,
            'price' => 200
        ]);
        $cheaperLaterTour = Tours::factory()->create([
            'travel_id' => $travel->id,
            'price' => 100,
            'start_date' => now()->addDays(2),
            'end_date' => now()->addDays(3)
        ]);
        $cheaperEarlierTour = Tours::factory()->create([
            'travel_id' => $travel->id,
            'price' => 100,
            'start_date' => now(),
            'end_date' => now()->addDay()
        ]);

        $response = $this->get('/api/v1/travels/' . $travel->slug . '/tours?sort_by=price&sort_order=asc');

        $response->assertStatus(200);
        $response->assertJsonPath('data.0.id', $cheaperEarlierTour->id);
        $response->assertJsonPath('data.1.id', $cheaperLaterTour->id);
        $response->assertJsonPath('data.2.id', $expensiveTour->id);
    }
    public function test_tours_list_filters_by_price_correctly(): void
    {
        $travel = Travel::factory()->create();
        $expensiveTour = Tours::factory()->create([
            'travel_id' => $travel->id,
            'price' => 200
        ]);
        $cheaperTour = Tours::factory()->create([
            'travel_id' => $travel->id,
            'price' => 100,
        ]);
        $endpoint = '/api/v1/travels/' . $travel->slug . '/tours';

        $response = $this->get($endpoint . '?price_from=100');
        $response->assertJsonCount(2, 'data');
        $response->assertJsonFragment(['id' => $cheaperTour->id]);
        $response->assertJsonFragment(['id' => $expensiveTour->id]);

        $response = $this->get($endpoint . '?price_from=150');
        $response->assertJsonCount(1, 'data');
        $response->assertJsonMissing(['id' => $cheaperTour->id]);
        $response->assertJsonFragment(['id' => $expensiveTour->id]);

        $response = $this->get($endpoint . '?price_from=250');
        $response->assertJsonCount(0, 'data');

        $response = $this->get($endpoint . '?price_to=200');
        $response->assertJsonCount(2, 'data');
        $response->assertJsonFragment(['id' => $cheaperTour->id]);
        $response->assertJsonFragment(['id' => $expensiveTour->id]);

        $response = $this->get($endpoint . '?price_to=150');
        $response->assertJsonCount(1, 'data');
        $response->assertJsonFragment(['id' => $cheaperTour->id]);
        $response->assertJsonMissing(['id' => $expensiveTour->id]);

        $response = $this->get($endpoint . '?price_to=50');
        $response->assertJsonCount(0, 'data');

        $response = $this->get($endpoint . '?price_from=150&price_to=250');
        $response->assertJsonCount(1, 'data');
        $response->assertJsonMissing(['id' => $cheaperTour->id]);
        $response->assertJsonFragment(['id' => $expensiveTour->id]);
    }
    public function test_tours_list_filters_by_start_date_correctly(): void
    {
        $travel = Travel::factory()->create();
        $laterTour = Tours::factory()->create([
            'travel_id' => $travel->id,
            'start_date' => now()->addDays(2),
            'end_date' => now()->addDays(3),
        ]);
        $earlierTour = Tours::factory()->create([
            'travel_id' => $travel->id,
            'start_date' => now(),
            'end_date' => now()->addDay(),
        ]);
        $endpoint = '/api/v1/travels/' . $travel->slug . '/tours';

        $response = $this->get($endpoint . '?date_from=' . now());
        $response->assertJsonCount(2, 'data');
        $response->assertJsonFragment(['id' => $laterTour->id]);
        $response->assertJsonFragment(['id' => $earlierTour->id]);

        $response = $this->get($endpoint . '?date_from=' . now()->addDay());
        $response->assertJsonCount(1, 'data');
        $response->assertJsonFragment(['id' => $laterTour->id]);
        $response->assertJsonMissing(['id' => $earlierTour->id]);

        $response = $this->get($endpoint . '?date_from=' . now()->addDays(5));
        $response->assertJsonCount(0, 'data');

        $response = $this->get($endpoint . '?date_to=' . now()->addDays(5));
        $response->assertJsonCount(2, 'data');
        $response->assertJsonFragment(['id' => $laterTour->id]);
        $response->assertJsonFragment(['id' => $earlierTour->id]);

        $response = $this->get($endpoint . '?date_to=' . now()->addDay());
        $response->assertJsonCount(1, 'data');
        $response->assertJsonMissing(['id' => $laterTour->id]);
        $response->assertJsonFragment(['id' => $earlierTour->id]);

        $response = $this->get($endpoint . '?date_to=' . now()->subDay());
        $response->assertJsonCount(0, 'data');

        $response = $this->get($endpoint . '?date_from=' . now()->addDay() . '&date_to=' . now()->addDays(5));
        $response->assertJsonCount(1, 'data');
        $response->assertJsonFragment(['id' => $laterTour->id]);
        $response->assertJsonMissing(['id' => $earlierTour->id]);
    }
    public function test_tours_list_returns_validation_errors_correctly(): void
    {
        $travel = Travel::factory()->create();

        $response = $this->getJson('api/v1/travels/' . $travel->slug . '/tours?date_from=abcde');
        $response->assertStatus(422);

        $response = $this->getJson('api/v1/travels/' . $travel->slug . '/tours?price_from=abcde');
        $response->assertStatus(422);
    }
}
