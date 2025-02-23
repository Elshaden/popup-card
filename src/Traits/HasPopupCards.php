<?php

namespace Elshaden\PopupCard\Traits;


use Elshaden\PopupCard\Models\PopupCard;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

trait HasPopupCards
{

    public function popupCard():BelongsToMany
    {
        return $this->belongsToMany(
            PopupCard::class,
            'cards_users', // Pivot table name
            'user_id',         // Foreign key on pivot table for User
            'popup_card_id'    // Foreign key on pivot table for PopupCard
        )->withPivot('seen');
    }

}
