<?php

namespace Tests\Feature;

use App\Models\Booking;
use App\Models\Enquiry;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminTest extends TestCase
{
    use RefreshDatabase;

    public function test_guests_are_sent_to_login_and_staff_can_open_the_office(): void
    {
        $user = User::factory()->create([
            'email' => 'admin@kingscrossds.co.uk',
        ]);

        $this->get('/admin')->assertRedirect('/admin/login');

        $this->get('/admin/login')->assertOk()->assertSee('Office login', false);

        $this->post('/admin/login', [
            'email' => 'admin@kingscrossds.co.uk',
            'password' => 'password',
        ])->assertRedirect('/admin');

        $this->actingAs($user)
            ->get('/admin')
            ->assertOk()
            ->assertSee('Dashboard')
            ->assertSee('Bookings')
            ->assertSee('Enquiries');
    }

    public function test_staff_can_view_and_update_a_booking(): void
    {
        $user = User::factory()->create();
        $booking = Booking::query()->create([
            'public_token' => 'abcdefghijklmnopqrstuvwxyz1234567890abcd',
            'package' => 'lesson',
            'student_name' => 'Amina Khan',
            'email' => 'amina@example.com',
            'phone' => '07123456789',
            'postcode' => 'N1 0DR',
            'starts_at' => now()->addDay()->setTime(10, 0),
            'ends_at' => now()->addDay()->setTime(12, 0),
            'amount_pence' => 8000,
            'deposit_pence' => 1000,
            'status' => 'pending',
            'hold_expires_at' => now()->addHour(),
        ]);

        $this->actingAs($user)
            ->get('/admin/bookings/'.$booking->id)
            ->assertOk()
            ->assertSee('Amina Khan')
            ->assertSee('KCDS-');

        $this->actingAs($user)
            ->patch('/admin/bookings/'.$booking->id, ['status' => 'paid'])
            ->assertRedirect();

        $this->assertSame('paid', $booking->fresh()->status);
        $this->assertNotNull($booking->fresh()->paid_at);
    }

    public function test_opening_an_enquiry_marks_it_read(): void
    {
        $user = User::factory()->create();
        $enquiry = Enquiry::query()->create([
            'name' => 'Tommy',
            'phone' => '07908726875',
            'email' => 'tommy@example.com',
            'message' => 'Can I book evenings?',
        ]);

        $this->actingAs($user)
            ->get('/admin/enquiries/'.$enquiry->id)
            ->assertOk()
            ->assertSee('Can I book evenings?');

        $this->assertNotNull($enquiry->fresh()->read_at);
    }
}
