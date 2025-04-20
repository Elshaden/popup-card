<?php

namespace Elshaden\PopupCard\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PopupCardController extends \App\Http\Controllers\Controller
{
    public function getModalContent(Request $request, string $name)
    {
        if (!config('popup_card.enabled')) {
            return response()->json(['show_modal' => false]);
        }
        $user = auth()->user();


        // Retrieve the latest active and published popup
        $popupCardQuery = \Elshaden\PopupCard\Models\PopupCard::active()->where('name', $name);
        $popupCard = $popupCardQuery->latest()->first();

        if (!$popupCard) {
            return response()->json(['show_modal' => false, 'reason' => 'No Popup Found']); // No active modal found
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

        return response()->json(['show_modal' => false]); // Default response


    }

    public function markModalSeen(Request $request)
    {
        $user = Auth::user(); // Get the currently authenticated user

        if (!$user) {
            // Return error if not authenticated
            return response()->json(['error' => 'User is not authenticated'], 403);
        }

        $user->popupCard()->where('id', $request->popup_card_id)->attach($request->popup_card_id, ['seen' => true]);

        return response()->json(['status' => 'success']);
    }

}
