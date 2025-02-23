# Laravel Nova Popup Card

### Simple Popup Card you can use anywhere in Nova apps.
This package will allow you to create a simple popup card that will show to the user once the dashboard is loaded.
The user can close the popup card and choose to not show it again.
It is the same as  Nova card, except that it automatically  pops up when the page is loaded.
The use cases are many such as Welcome message, Important message, New feature announcement, etc.

## Nova V 4
This package is compatible with Nova V4
Is not tested on previous versions of Nova or Nova V5. though it should work on Nova V5 without any issues.


## Installation

You can install the package in Laravel Nova app that uses Nova via composer:

```bash
composer require elshaden/nova-popup-card
```

## Configuration
You can publish the config file with:
```bash
php artisan vendor:publish --provider="Elshaden\PopupCard\PopupCardServiceProvider" --tag="config"
```

## Migrations
You can publish the migration with:
```bash
php artisan vendor:publish --provider="Elshaden\PopupCard\PopupCardServiceProvider" --tag="migrations"
```

After publishing the migration you can create the popup_card_statuses table by running the migrations:

```bash 
php artisan migrate
```




## Usage
In your Main Dashboard file you can use the PopupCard 

Or in any Nova Resource file you can use the PopupCard 
```php
use Elshaden\PopupCard\PopupCard;

public function cards(Request $request)
{
    return [
        ......
        (new PopupCard())->width('1/4') // '1/4','1/3','1/2','2/3','3/4','full',
           
      ];
}
````

You must Add the Trait to your user model
This will store the popup card status as seen and should not show again if user choose to close it and does not want to see it again
```php

use Elshaden\PopupCard\HasPopupCard;

class User extends Authenticatable
{
    use HasPopupCard;
}
```


To Manage the Popup Card you have to use the Nova Resource available in the package

```php
Elshaden\PopupCard\Nova\PopupCardResource

```

 You need to add the following code to your NovaServiceProvider.php file to register the resource
 
```php  

use Elshaden\PopupCard\Nova\PopupCardResource;

    protected function resources(): void
    {
        parent::resources();
        Nova::resources([
            PopupCardResource::class,

        ]);


    }
```

You can now add to your menu the Popup Card Resource to manage the popup cards

```php
use Elshaden\PopupCard\Nova\PopupCardResource;

  Nova::mainMenu(function (Request $request) {
        return [
            .......
            .......
            MenuItem::resource(PopupCardResource::class),
            .......
        ];
    });
 
```
> You can Create,Edit or Delete  a Popup
> The system will show the latest active & published popup card to the user once the dashboard is loaded
> 
> The user can close the popup card.
> The user can choose to not show the popup card again

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.


## Credits
- [Elshaden](

## Contributing
Please feel free to fork this package and contribute by submitting a pull request to enhance the functionalities.

## Security

If you discover any security related issues, please email
instead of using the issue tracker.

## Support

Thank you for using this package, if you have issues or discover issues please use the issue tracker to report the issue.
