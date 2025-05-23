<?php

namespace Elshaden\PopupCard\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * PopupCard Model
 * 
 * Represents a popup card that can be displayed to users in a Laravel Nova application.
 * 
 * @property int $id
 * @property string $name
 * @property string $title
 * @property string|null $body
 * @property bool $published
 * @property bool $active
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 */
class PopupCard extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'name',
        'title',
        'body',
        'published',
        'active',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'published' => 'boolean',
        'active' => 'boolean',
    ];

    /**
     * Scope a query to only include active and published popup cards.
     *
     * @param Builder $query
     * @return Builder
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('active', true)->where('published', true);
    }

    /**
     * Scope a query to only include published popup cards.
     *
     * @param Builder $query
     * @return Builder
     */
    public function scopePublished(Builder $query): Builder
    {
        return $query->where('published', true);
    }

    /**
     * Get the latest published popup card if the current card is active.
     *
     * @return PopupCard|null
     */
    public function getPopUp(): ?PopupCard
    {
        return $this->active
            ? $this->published()->latest()->first()
            : null;
    }

    /**
     * The users that have seen this popup card.
     *
     * @return BelongsToMany
     */
    public function users(): BelongsToMany
    {
        $userModel = config('popup_card.user_model', 'App\Models\User');

        return $this->belongsToMany(
            $userModel,
            config('popup_card.pivot_table', 'cards_users'), // Pivot table name
            'popup_card_id',   // Foreign key on pivot table for PopupCard
            'user_id'          // Foreign key on pivot table for User
        )->withPivot('seen');
    }
}
