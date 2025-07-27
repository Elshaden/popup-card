<?php

namespace Elshaden\PopupCard\Http\Controllers;

use Elshaden\PopupCard\Models\PopupCard;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

/**
 * Controller for handling popup card API requests.
 */
class PopupCardController extends \App\Http\Controllers\Controller
{
    /**
     * Get the content for a popup modal.
     *
     * This method checks if a popup card should be displayed to the user
     * based on the card name and whether the user has already seen it.
     *
     * @param Request $request The HTTP request instance
     * @param string $name The name of the popup card to retrieve
     * @return JsonResponse The JSON response containing the modal content or a status indicating it should not be shown
     */
    public function getModalContent(Request $request, string $name): JsonResponse
    {
        // Check if popup cards are enabled in the configuration
        if (!config('popup_card.enabled')) {
            return response()->json(['show_modal' => false, 'reason' => 'Popups disabled in config']);
        }

        // Get the authenticated user
        $user = Auth::user();

        if (!$user) {
            return response()->json(['show_modal' => false, 'reason' => 'User not authenticated']);
        }

        // Retrieve the latest active and published popup
        $popupCardQuery = PopupCard::active()->where('name', $name);
        $popupCard = $popupCardQuery->latest()->first();

        if (!$popupCard) {
            return response()->json(['show_modal' => false, 'reason' => 'No active popup found']);
        }

        // Check for unseen cards in user-pivot relationship
        if ($user->popupCard()->where('name', $name)->exists()) {
            $unseenPopupCard = $user->popupCard()
                ->wherePivot('seen', false)
                ->where('name', $name)
                ->first();
        } else {
            $unseenPopupCard = $popupCard;
        }

        if ($unseenPopupCard) {
            return response()->json([
                'show_modal' => true,
                'title' => $unseenPopupCard->title,
                'body' => $unseenPopupCard->body,
                'popup_card_id' => $unseenPopupCard->id
            ]);
        }

        return response()->json(['show_modal' => false, 'reason' => 'User has already seen this popup']);
    }

    /**
     * Mark a popup modal as seen by the user.
     *
     * This method records that the user has seen a specific popup card
     * and should not see it again.
     *
     * @param Request $request The HTTP request instance containing the popup_card_id
     * @return JsonResponse The JSON response indicating success or failure
     */
    public function markModalSeen(Request $request): JsonResponse
    {
        // Get the authenticated user
        $user = Auth::user();

        if (!$user) {
            return response()->json(['error' => 'User is not authenticated'], 403);
        }

        // Validate the request
        $validator = Validator::make($request->all(), [
            'popup_card_id' => 'required|integer|exists:'.config('popup_card.table_name', 'popup_cards').',id',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => 'Invalid popup card ID', 'details' => $validator->errors()], 422);
        }

        // Use syncWithoutDetaching to prevent duplicate entries
        $user->popupCard()->syncWithoutDetaching([
            $request->popup_card_id => ['seen' => true]
        ]);

        return response()->json(['status' => 'success', 'message' => 'Popup marked as seen']);
    }
}
