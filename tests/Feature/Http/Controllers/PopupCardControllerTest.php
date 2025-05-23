<?php

namespace Elshaden\PopupCard\Tests\Feature\Http\Controllers;

use App\Models\User;
use Elshaden\PopupCard\Models\PopupCard;
use Elshaden\PopupCard\Tests\TestCase;
use Elshaden\PopupCard\Traits\HasPopupCards;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PopupCardControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_returns_popup_card_content_when_available()
    {
        // Create a test user with the HasPopupCards trait
        $user = $this->createUser();

        // Create an active popup card
        $popupCard = PopupCard::create([
            'name' => 'dashboard',
            'title' => 'Welcome to Dashboard',
            'body' => 'This is a welcome message for the dashboard.',
            'published' => true,
            'active' => true,
        ]);

        // Act as the authenticated user
        $this->actingAs($user);

        // Make a request to get the modal content
        $response = $this->getJson('/api/modal-content/dashboard');

        // Assert the response structure and content
        $response->assertStatus(200)
            ->assertJson([
                'show_modal' => true,
                'title' => 'Welcome to Dashboard',
                'body' => 'This is a welcome message for the dashboard.',
                'popup_card_id' => $popupCard->id,
            ]);
    }

    /** @test */
    public function it_returns_no_popup_when_disabled_in_config()
    {
        // Create a test user with the HasPopupCards trait
        $user = $this->createUser();

        // Create an active popup card
        PopupCard::create([
            'name' => 'dashboard',
            'title' => 'Welcome to Dashboard',
            'body' => 'This is a welcome message for the dashboard.',
            'published' => true,
            'active' => true,
        ]);

        // Disable popups in the config
        config(['popup_card.enabled' => false]);

        // Act as the authenticated user
        $this->actingAs($user);

        // Make a request to get the modal content
        $response = $this->getJson('/api/modal-content/dashboard');

        // Assert the response structure and content
        $response->assertStatus(200)
            ->assertJson([
                'show_modal' => false,
            ]);
    }

    /** @test */
    public function it_returns_no_popup_when_user_has_seen_it()
    {
        // Create a test user with the HasPopupCards trait
        $user = $this->createUser();

        // Create an active popup card
        $popupCard = PopupCard::create([
            'name' => 'dashboard',
            'title' => 'Welcome to Dashboard',
            'body' => 'This is a welcome message for the dashboard.',
            'published' => true,
            'active' => true,
        ]);

        // Mark the popup as seen by the user
        $user->popupCard()->attach($popupCard->id, ['seen' => true]);

        // Act as the authenticated user
        $this->actingAs($user);

        // Make a request to get the modal content
        $response = $this->getJson('/api/modal-content/dashboard');

        // Assert the response structure and content
        $response->assertStatus(200)
            ->assertJson([
                'show_modal' => false,
            ]);
    }

    /** @test */
    public function it_can_mark_a_popup_as_seen()
    {
        // Create a test user with the HasPopupCards trait
        $user = $this->createUser();

        // Create an active popup card
        $popupCard = PopupCard::create([
            'name' => 'dashboard',
            'title' => 'Welcome to Dashboard',
            'body' => 'This is a welcome message for the dashboard.',
            'published' => true,
            'active' => true,
        ]);

        // Act as the authenticated user
        $this->actingAs($user);

        // Make a request to mark the modal as seen
        $response = $this->postJson('/api/mark-modal-seen', [
            'popup_card_id' => $popupCard->id,
        ]);

        // Assert the response structure
        $response->assertStatus(200)
            ->assertJson([
                'status' => 'success',
            ]);

        // Assert that the popup is marked as seen in the database
        $this->assertDatabaseHas('cards_users', [
            'popup_card_id' => $popupCard->id,
            'user_id' => $user->id,
            'seen' => true,
        ]);
    }

    /** @test */
    public function it_returns_error_when_marking_popup_as_seen_without_authentication()
    {
        // Create an active popup card
        $popupCard = PopupCard::create([
            'name' => 'dashboard',
            'title' => 'Welcome to Dashboard',
            'body' => 'This is a welcome message for the dashboard.',
            'published' => true,
            'active' => true,
        ]);

        // Make a request to mark the modal as seen without authentication
        $response = $this->postJson('/api/mark-modal-seen', [
            'popup_card_id' => $popupCard->id,
        ]);

        // Assert the response structure
        $response->assertStatus(403)
            ->assertJson([
                'error' => 'User is not authenticated',
            ]);
    }

    /**
     * Create a user with the HasPopupCards trait for testing
     *
     * @return \App\Models\User
     */
    private function createUser()
    {
        // Create a test user class with the HasPopupCards trait
        $userClass = new class extends User {
            use HasPopupCards;
        };

        // Create and return a user instance
        return $userClass::create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => bcrypt('password'),
        ]);
    }
}
