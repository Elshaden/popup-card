<?php

namespace Elshaden\PopupCard\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class PopupCard extends Model
{

    protected $fillable = [
        'name',
        'title',
        'body',
        'published',
        'active',
    ];


    public function scopeActive($query)
    {
        return $query->where('active', true)->where('published', true);
    }

    public function scopePublished($query)
    {
        return $query->where('published', true);
    }

    public function getPopUp()
    {
        return $this->active
            ? $this->published()->latest()->first()
            : false;
    }

    public function users()
    {
        return $this->belongsToMany(
            User::class,
            'cards_users', // Pivot table name
            'popup_card_id',   // Foreign key on pivot table for PopupCard
            'user_id'          // Foreign key on pivot table for User
        )->withPivot('seen');

    }

}
