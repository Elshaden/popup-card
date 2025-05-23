# Laravel Nova Popup Card - Troubleshooting Guide

This document provides solutions to common issues you might encounter when using or developing with the Laravel Nova Popup Card package.

## Testing Issues

### Class "Elshaden\PopupCard\Tests\TestCase" not found

If you encounter this error when running tests:

```
Class "Elshaden\PopupCard\Tests\TestCase" not found
```

This usually indicates that the Composer autoloader hasn't been updated to include the test classes. To fix this:

1. Run the following command to regenerate the autoloader:

```bash
composer dump-autoload
```

2. If that doesn't resolve the issue, try installing the development dependencies:

```bash
composer install --dev
```

3. If you're running tests from a project that has installed this package (rather than developing the package itself), make sure you've installed the package with dev dependencies:

```bash
composer require elshaden/popup-card --dev
```

4. If you're still experiencing issues, check that the `autoload-dev` section in your `composer.json` file is correctly configured:

```
"autoload-dev": {
    "psr-4": {
        "Elshaden\\PopupCard\\Tests\\": "tests/"
    }
}
```

After making any changes to the `composer.json` file, remember to run `composer dump-autoload` again.

## Installation Issues

### Service Provider Not Found

If you encounter issues with the service provider not being found:

1. Make sure the package is properly installed:

```bash
composer require elshaden/popup-card
```

2. Check that the service provider is registered in your `config/app.php` file:

```php
'providers' => [
    // ...
    Elshaden\PopupCard\CardServiceProvider::class,
],
```

3. Clear the configuration cache:

```bash
php artisan config:clear
```

## Usage Issues

### Popup Card Not Showing

If the popup card is not showing:

1. Check that the popup card is active and published in the database
2. Verify that the user hasn't already marked the popup as "do not show again"
3. Check that the popup card name in your code matches the name in the database
4. Make sure the `popup_card.enabled` configuration is set to `true`
5. Check the browser console for any JavaScript errors

### Database Migration Issues

If you encounter issues with the database migrations:

1. Make sure you've published the migrations:

```bash
php artisan vendor:publish --provider="Elshaden\PopupCard\CardServiceProvider" --tag="popup-card-migrations"
```

2. Run the migrations:

```bash
php artisan migrate
```

3. If you're still having issues, try running the migrations with the `--verbose` flag to get more information:

```bash
php artisan migrate --verbose
```

## Still Having Issues?

If you're still experiencing problems after trying these solutions, please open an issue on the GitHub repository with detailed information about your environment and the specific error you're encountering.
