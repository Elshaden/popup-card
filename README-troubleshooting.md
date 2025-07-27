# Troubleshooting Laravel Nova Popup Card

This document provides solutions for common issues you might encounter when using the Laravel Nova Popup Card package.

## Installation Issues

### Package Not Found

**Issue**: `composer require` command fails with "Package not found" error.

**Solution**:
- Verify that you're using the correct package name: `elshaden/popup-card`
- Check that your `composer.json` file has the correct repositories section if you're installing from a private repository
- Run `composer clear-cache` and try again

### Service Provider Not Registered

**Issue**: The package is installed but doesn't appear to be working.

**Solution**:
- Check if Laravel's package auto-discovery is enabled in your `composer.json`
- If auto-discovery is disabled, manually register the service provider in your `config/app.php` file:
  ```php
  'providers' => [
      // ...
      Elshaden\PopupCard\CardServiceProvider::class,
  ],
  ```

## Database Issues

### Migration Failed

**Issue**: Running migrations fails with an error.

**Solutions**:
- Ensure you've published the migrations: `php artisan vendor:publish --provider="Elshaden\PopupCard\CardServiceProvider" --tag="popup-card-migrations"`
- Check your database connection settings
- If you're using custom table names, ensure they're properly configured in your `config/popup_card.php` file
- If you're getting a "table already exists" error, you may need to roll back previous migrations

### Relationship Issues

**Issue**: Errors related to the relationship between users and popup cards.

**Solutions**:
- Ensure you've added the `HasPopupCards` trait to your User model
- Verify that your User model uses UUID as primary key if you're getting foreign key constraint errors
- Check that the table names and foreign keys in your config match your actual database schema

## Display Issues

### Popup Not Showing

**Issue**: The popup card doesn't appear when expected.

**Solutions**:
- Check that you've added the popup card to your dashboard or resource correctly
- Verify that the popup card is active and published in the database
- Ensure the user hasn't already seen the popup (check the pivot table)
- Check browser console for JavaScript errors
- Verify that `enabled` is set to `true` in your config file

### Styling Issues

**Issue**: The popup card doesn't look right or has styling conflicts.

**Solutions**:
- Check for CSS conflicts with your application's styles
- Verify that all required assets are loaded
- Try adjusting the card width in your configuration
- Inspect the element using browser developer tools to identify styling issues

## API Issues

### API Endpoints Not Working

**Issue**: The API endpoints for the popup card return 404 errors.

**Solutions**:
- Ensure your routes are properly registered
- Check that you're using the correct URL for the API endpoints
- Verify that the user is authenticated when making API requests

## Nova Resource Issues

### PopupCardResource Not Found

**Issue**: The Nova resource for managing popup cards isn't available.

**Solutions**:
- Ensure you've registered the resource in your Nova service provider
- Check that you're importing the correct namespace: `use Elshaden\PopupCard\Nova\PopupCardResource;`
- Verify that you have the correct permissions to view the resource

## Performance Issues

### Slow Loading

**Issue**: The popup card takes too long to load.

**Solutions**:
- Check your database query performance
- Consider adding indexes to frequently queried columns
- Optimize your database configuration
- Reduce the complexity of the popup card content

## Upgrading Issues

### Breaking Changes

**Issue**: Upgrading to a new version breaks functionality.

**Solutions**:
- Always read the release notes before upgrading
- Back up your database before upgrading
- After upgrading, republish assets and config files if necessary
- Clear your application cache after upgrading: `php artisan cache:clear`

---

If you encounter an issue not covered here, please check the [GitHub issues](https://github.com/elshaden/popup-card/issues) or open a new issue with detailed information about your problem.