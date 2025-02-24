<?php

namespace Elshaden\PopupCard\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class PopupCardController extends \App\Http\Controllers\Controller
{
      public function getModalContent()
      {
            if (!config('popup_card.enabled')) {
                  return response()->json(['show_modal' => false]);
            }
            $user = Auth::user(); // Get the authenticated user

            if (!$user) {
                  return response()->json(['error' => 'User is not authenticated'], 403);
            }


            // Retrieve the latest active and published popup card
            $popupCard = \Elshaden\PopupCard\Models\PopupCard::active()->latest('popup_cards.created_at')->first();

            if (!$popupCard) {
                  // No active or published popup card found
                  return response()->json(['show_modal' => false]);
            }

            // Check if the user has already seen this popup card
            $hasSeenPopup = $popupCard->users()->where('users.id', $user->id)->wherePivot('seen', true)->exists();

            if ($hasSeenPopup) {
                  // User has already seen the popup card
                  return response()->json(['show_modal' => false]);
            }

            // User has not seen the popup card, return it
            return response()->json([
                  'show_modal' => true,
                  'title' => $popupCard->title,
                  'body' => $popupCard->body,
                  'popup_card_id' => $popupCard->id,
            ]);


      }

      public function markModalSeen(Request $request)
      {
            $user = Auth::user(); // Get the currently authenticated user

            if (!$user) {
                  // Return error if not authenticated
                  return response()->json(['error' => 'User is not authenticated'], 403);
            }

            $user->popupCard()->attach($request->popup_card_id, ['seen' => true]);

            return response()->json(['status' => 'success']);
      }

}
