<?php

namespace Elshaden\PopupCard\Tests\Unit\Models;

use Elshaden\PopupCard\Models\PopupCard;
use Elshaden\PopupCard\Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PopupCardTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_scope_to_active_popup_cards()
    {
        // Create an active popup card
        $activeCard = PopupCard::create([
            'name' => 'active-card',
            'title' => 'Active Card',
            'body' => 'This is an active card',
            'published' => true,
            'active' => true,
        ]);

        // Create an inactive popup card
        $inactiveCard = PopupCard::create([
            'name' => 'inactive-card',
            'title' => 'Inactive Card',
            'body' => 'This is an inactive card',
            'published' => true,
            'active' => false,
        ]);

        // Create an unpublished popup card
        $unpublishedCard = PopupCard::create([
            'name' => 'unpublished-card',
            'title' => 'Unpublished Card',
            'body' => 'This is an unpublished card',
            'published' => false,
            'active' => true,
        ]);

        // Get active popup cards
        $activeCards = PopupCard::active()->get();

        // Assert that only the active card is returned
        $this->assertCount(1, $activeCards);
        $this->assertTrue($activeCards->contains($activeCard));
        $this->assertFalse($activeCards->contains($inactiveCard));
        $this->assertFalse($activeCards->contains($unpublishedCard));
    }

    /** @test */
    public function it_can_scope_to_published_popup_cards()
    {
        // Create a published popup card
        $publishedCard = PopupCard::create([
            'name' => 'published-card',
            'title' => 'Published Card',
            'body' => 'This is a published card',
            'published' => true,
            'active' => false,
        ]);

        // Create an unpublished popup card
        $unpublishedCard = PopupCard::create([
            'name' => 'unpublished-card',
            'title' => 'Unpublished Card',
            'body' => 'This is an unpublished card',
            'published' => false,
            'active' => true,
        ]);

        // Get published popup cards
        $publishedCards = PopupCard::published()->get();

        // Assert that only the published card is returned
        $this->assertCount(1, $publishedCards);
        $this->assertTrue($publishedCards->contains($publishedCard));
        $this->assertFalse($publishedCards->contains($unpublishedCard));
    }

    /** @test */
    public function it_can_get_popup_when_active()
    {
        // Create an active popup card
        $activeCard = PopupCard::create([
            'name' => 'active-card',
            'title' => 'Active Card',
            'body' => 'This is an active card',
            'published' => true,
            'active' => true,
        ]);

        // Create a model instance and set it as active
        $popupCard = new PopupCard();
        $popupCard->active = true;

        // Get the popup
        $popup = $popupCard->getPopUp();

        // Assert that a popup is returned
        $this->assertNotNull($popup);
        $this->assertEquals($activeCard->id, $popup->id);
    }

    /** @test */
    public function it_returns_false_when_getting_popup_and_not_active()
    {
        // Create an active popup card
        PopupCard::create([
            'name' => 'active-card',
            'title' => 'Active Card',
            'body' => 'This is an active card',
            'published' => true,
            'active' => true,
        ]);

        // Create a model instance and set it as inactive
        $popupCard = new PopupCard();
        $popupCard->active = false;

        // Get the popup
        $popup = $popupCard->getPopUp();

        // Assert that false is returned
        $this->assertFalse($popup);
    }

    /** @test */
    public function it_has_a_relationship_with_users()
    {
        $popupCard = new PopupCard();

        // Assert that the relationship method exists and returns the correct type
        $this->assertIsCallable([$popupCard, 'users']);
        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Relations\BelongsToMany::class, $popupCard->users());
    }
}
