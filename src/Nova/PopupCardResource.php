<?php

namespace Elshaden\PopupCard\Nova;

use App\Nova\Resource;

use Elshaden\PopupCard\Models\PopupCard;
use Laravel\Nova\Fields\BelongsToMany;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Text;

use Laravel\Nova\Fields\Textarea;
use Laravel\Nova\Fields\Trix;
use Laravel\Nova\Http\Requests\NovaRequest;

class PopupCardResource extends Resource
{

    public static function label()
    {
        return __('Popup Cards');
    }

    public static function singularLabel()
    {
        return __('Popup Card');
    }


    /**
     * The model the resource corresponds to.
     *
     * @var class-string<\Elshaden\PopupCard\Models\PopupCard>
     */
    public static $model = PopupCard::class;

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'title';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id','title','body'
    ];

    /**
     * Get the fields displayed by the resource.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return array
     */
    public function fields(NovaRequest $request)
    {
        return [
            ID::make()->sortable(),
            Text::make('Name')->creationRules('required', 'max:120', 'unique:'.config('popup_card.table_name', 'popup_cards').',name')
            ->updateRules('required', 'max:120', 'unique:'.config('popup_card.table_name', 'popup_cards').',name,{{resourceId}}'),
            Text::make('Title')->rules('required', 'max:120'),

            Textarea::make(__('Content'), 'body')->rules('required'),

            Boolean::make('Published')->default(true),

            Boolean::make('Active')->default(true),

            Text::make('Users Count',fn()=>$this->users()->count())->exceptOnForms()->canSee(function(){
                return config('popup_card.show_users_count', false);
            }),

            BelongsToMany::make('Seen By', 'users', config('popup_card.user_nova_resource', 'App\Nova\User'))
            ->canSee(function(){
                return config('popup_card.show_seen_by_users', false);
            })
        ];
    }

    /**
     * Get the cards available for the request.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return array
     */
    public function cards(NovaRequest $request)
    {
        return [];
    }

    /**
     * Get the filters available for the resource.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return array
     */
    public function filters(NovaRequest $request)
    {
        return [];
    }

    /**
     * Get the lenses available for the resource.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return array
     */
    public function lenses(NovaRequest $request)
    {
        return [];
    }

    /**
     * Get the actions available for the resource.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return array
     */
    public function actions(NovaRequest $request)
    {
        return [];
    }
}
