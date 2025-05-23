<?php

namespace Elshaden\PopupCard\Traits;

use Elshaden\PopupCard\Models\PopupCard;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * Trait HasPopupCards
 * 
 * This trait should be added to the User model to enable popup card functionality.
 * It provides methods for retrieving, checking, and marking popup cards as seen.
 */
trait HasPopupCards
{
    /**
     * Get the popup cards associated with the user.
     *
     * @return BelongsToMany
     */
    public function popupCard(): BelongsToMany
    {
        return $this->belongsToMany(
            PopupCard::class,
            config('popup_card.pivot_table', 'cards_users'), // Pivot table name
            config('popup_card.user_foreign_key', 'user_id'), // Foreign key on pivot table for User
            config('popup_card.popup_card_foreign_key', 'popup_card_id') // Foreign key on pivot table for PopupCard
        )->withPivot('seen');
    }

    /**
     * Check if the user has seen a specific popup card.
     *
     * @param int|string $popupCardId The ID of the popup card
     * @return bool True if the user has seen the popup card, false otherwise
     */
    public function hasSeenPopupCard($popupCardId): bool
    {
        return $this->popupCard()
            ->wherePivot('popup_card_id', $popupCardId)
            ->wherePivot('seen', true)
            ->exists();
    }

    /**
     * Check if the user has seen a popup card with the given name.
     *
     * @param string $name The name of the popup card
     * @return bool True if the user has seen the popup card, false otherwise
     */
    public function hasSeenPopupCardByName(string $name): bool
    {
        return $this->popupCard()
            ->where('name', $name)
            ->wherePivot('seen', true)
            ->exists();
    }

    /**
     * Mark a popup card as seen by the user.
     *
     * @param int|string $popupCardId The ID of the popup card
     * @return void
     */
    public function markPopupCardAsSeen($popupCardId): void
    {
        $this->popupCard()->syncWithoutDetaching([
            $popupCardId => ['seen' => true]
        ]);
    }

    /**
     * Get all popup cards that the user has not seen yet.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getUnseenPopupCards()
    {
        return PopupCard::active()
            ->whereDoesntHave('users', function ($query) {
                $query->where('users.id', $this->id)
                    ->where('seen', true);
            })
            ->get();
    }
}
