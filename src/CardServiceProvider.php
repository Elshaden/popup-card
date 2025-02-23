<?php

namespace Elshaden\PopupCard;

use Elshaden\PopupCard\Http\Controllers\PopupCardController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Laravel\Nova\Events\ServingNova;
use Laravel\Nova\Nova;


class CardServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->app->booted(function () {
            $this->routes();
        });

        Nova::serving(function (ServingNova $event) {
            Nova::script('popup-card', __DIR__ . '/../dist/js/card.js');
            Nova::style('popup-card', __DIR__ . '/../dist/css/card.css');
        });

        $this->publishes([
            __DIR__ . '/../database/migrations/create_popup_cards_table.php.stub'
            => database_path('migrations/' . date('Y_m_d_His') . '_create_popup_cards_table.php'),
        ], 'popup-card-migrations');
        $this->publishes([
            __DIR__ . '/../config/popup_card.php' => config_path('popup_card.php'),
        ], 'popup-card-config');


    }

    /**
     * Register the card's routes.
     *
     * @return void
     */
    protected function routes()
    {
        if ($this->app->routesAreCached()) {
            return;
        }


        // Load API routes for this card
        \Illuminate\Support\Facades\Route::middleware(['nova'])
            ->prefix('api')
            ->group(function () {

                // Route to get the popup modal content
                Route::get('/modal-content', [PopupCardController::class, 'getModalContent']);

                // Route to mark a popup as seen
                Route::post('/mark-modal-seen', [PopupCardController::class, 'markModalSeen']);

            });

    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        // Merge the package default config with the application's config
        $this->mergeConfigFrom(
            __DIR__ . '/../config/popup_card.php', // Path to the package config file
            'popup_card' // Config key
        );

    }


}
