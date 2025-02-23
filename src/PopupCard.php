<?php

namespace Elshaden\PopupCard;

use Illuminate\Support\Facades\Log;
use Laravel\Nova\Card;

class PopupCard extends Card
{
    /**
     * The width of the card (1/3, 1/2, or full).
     *
     * @var string
     */

   Public $width = '1/3';

   public function width($width)
    {
        return $this->withMeta(['width' => $width ?? $this->width]);
    }


    /**
     * Get the component name for the element.
     *
     * @return string
     */
    public function component()
    {
        return 'popup-card';
    }
}
