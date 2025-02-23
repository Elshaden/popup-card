<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Card API Routes
|--------------------------------------------------------------------------
|
| Here is where you may register API routes for your card. These routes
| are loaded by the ServiceProvider of your card. You're free to add
| as many additional routes to this file as your card may require.
|
*/

// Route::get('/endpoint', function (Request $request) {
//     //
// });

// Modal Content Route
// Route to fetch modal content
//Route::get('/modal-content', function () {
//
//    $user = auth('web')->user(); // Ensure the guard matches NOVA_GUARD
//\Illuminate\Support\Facades\Log::info('Route Reached :' . $user->id);
//    if (!$user) {
//        return response()->json(['error' => 'User not authenticated via web guard'], 403);
//    }
//
//    return response()->json([
//        'show_modal' => !auth()->user()->do_not_show_modal, // User preference
//        'title' => 'Welcome Back!',
//        'body' => 'This is an important announcement or information.',
//    ]);
//}); // Ensure Nova's middleware is used for authentication
//
//// Route to mark modal as seen
//Route::post('/mark-modal-seen', function () {
//    // Update the database field indicating the user has closed the modal
//    auth()->user()->update([
//        'do_not_show_modal' => request('do_not_show'),
//    ]);
//
//    return response()->json(['status' => 'success']);
//});

Route::get('/modal-content', function () {
    $user = auth('web')->user(); // Authenticate user using the web guard

    // Log the route access and user state
    \Illuminate\Support\Facades\Log::info('Route Reached :' . ($user?->do_not_show_pop_up ?? 'No User'));

    if (!$user) {
        return response()->json(['error' => 'User not authenticated via web guard'], 403);
    }

    // Check if the user has opted out of the modal
//    if ($user->do_not_show_pop_up) {
//        return response()->json(['show_modal' => false]);
//    }

    // Retrieve the latest active and published popup
    $popupCard = \Elshaden\PopupCard\Models\PopupCard::active()->latest()->first();

    if(!$popupCard){
        return response()->json(['show_modal' => false]);
    }

    if ($user->relationLoaded('popupCard') || $user->popupCard()->exists()) {
        // Fetch the related popup card from the pivot table
        $popupCard = $user->popupCard()->wherePivot('seen', false)->first();

        if ($popupCard) {
            // Popup card exists and is not marked as seen
            // You can now use content from $popupCard for the modal
            return response()->json([
                'show_modal' => true,
                'title' => $popupCard->title,
                'body' => $popupCard->body,
            ]);
        }
    }

    // If no popup card is found, do not show the modal
    if (!$popupCard) {
        return response()->json(['show_modal' => false]);
    }

    // Build the response dynamically
    $response = [
        'show_modal' => true,
        'title' => $popupCard->title,
        'body' => $popupCard->body,
    ];

    // Log and return the response
    \Illuminate\Support\Facades\Log::info('PopupCard Response: ', $response);
    return response()->json($response);
});


