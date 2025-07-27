# Laravel Nova Popup Card

### Simple Popup Card you can use anywhere in Nova apps.
This package will allow you to create a simple popup card that will show to the user once the dashboard is loaded.
The user can close the popup card and choose to not show it again.
It is the same as  Nova card, except that it automatically  pops up when the page is loaded.
The use cases are many such as Welcome message, Important message, New feature announcement, etc.

## Nova V 4
This package is compatible with Nova V4 / V5
Is not tested on previous versions of Nova .


## Installation

You can install the package in Laravel Nova app that uses Nova via composer:

```bash
composer require elshaden/nova-popup-card
```

## Package Registration

The package will be automatically registered using Laravel's package auto-discovery. However, if you have disabled auto-discovery, you need to manually register the package's service provider in your `config/app.php` file:

```php
'providers' => [
    // ...
    Elshaden\PopupCard\CardServiceProvider::class,
],
```

## Configuration
You can publish the config file with:
```bash
   php artisan vendor:publish --provider="Elshaden\PopupCard\CardServiceProvider" --tag="popup-card-config"
```

## Migrations
You can publish the migration with:
```bash
php artisan vendor:publish --provider="Elshaden\PopupCard\CardServiceProvider" --tag="popup-card-migrations"
```

After publishing the migration, you can create the popup_card_statuses table by running the migrations:

```bash 
php artisan migrate
```




## Usage
In your Main Dashboard file you can use the PopupCard 

Or in any Nova Resource file you can use the PopupCard 

The name(<is your Popup Name  >) when you created it

You can specify for each page or resource what Popup To show

#### Example
Here the Popup will be only shows in the main dashboard.

```php
use Elshaden\PopupCard\PopupCard;

public function cards(Request $request)
{
    return [
        ......
        (new PopupCard())->name('dashboard')->width('1/4') // '1/4','1/3','1/2','2/3','3/4','full',

      ];
}


````


In the cards method in any resource file add this 

````php
  public function cards(): array
    {
  
        return [
            // .....
            (new PopupCard())->name('popup-name')->width('1/3')->canSee(fn()=>true),

            // .....
        ];
    }

````
 **_You can use canSee() to set who can see this popup, example any user without 2-Factor Authentication_** 
#### Show to specific users
 You can you use the nova authorization method canSee

````php
    use Elshaden\PopupCard\PopupCard;
    
    
  public function cards(): array
    {
        return [
            // .....
            (new PopupCard())->name('resource-filename')->width('1/3')->canSee(fn()=> true // or any criteria  ),

            // .....
        ];
    }

````

You **MUST** Add the Trait to your user model
This will store the popup card status as seen and should not show again if user choose to close it and does not want to see it again
```php

use Elshaden\PopupCard\Traits\HasPopupCards;

class User extends Authenticatable
{
    use HasPopupCards;
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

## Testing

This package includes a comprehensive test suite. To run the tests:

1. Install development dependencies:
```bash
composer install --dev
```

2. Run the tests:
```bash
vendor/bin/phpunit
```

### Available Test Suites

- **Unit Tests**: Test individual components in isolation
  ```bash
  vendor/bin/phpunit --testsuite=Unit
  ```

- **Feature Tests**: Test API endpoints and integration
  ```bash
  vendor/bin/phpunit --testsuite=Feature
  ```

### Test Coverage

Generate a test coverage report:
```bash
vendor/bin/phpunit --coverage-html coverage
```

For more detailed information about testing, including how to run tests from a project that has installed this package (using either PHPUnit or Pest PHP), refer to the [README-testing.md](README-testing.md) file.

If you encounter any issues while using this package, please check the [README-troubleshooting.md](README-troubleshooting.md) file for common solutions.

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
