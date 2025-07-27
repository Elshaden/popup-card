# Testing Laravel Nova Popup Card

This document provides detailed information about testing the Laravel Nova Popup Card package.

## Setting Up the Testing Environment

### Prerequisites

- PHP 8.0 or higher
- Composer
- Laravel Nova license

### Installation for Testing

1. Clone the repository:
   ```bash
   git clone https://github.com/elshaden/popup-card.git
   cd popup-card
   ```

2. Install dependencies:
   ```bash
   composer install
   ```

3. Set up the testing environment:
   ```bash
   cp phpunit.xml.dist phpunit.xml
   ```

## Running Tests

### PHPUnit

Run the entire test suite:
```bash
vendor/bin/phpunit
```

Run specific test suites:
```bash
vendor/bin/phpunit --testsuite=Unit
vendor/bin/phpunit --testsuite=Feature
```

### Test Coverage

Generate a test coverage report:
```bash
vendor/bin/phpunit --coverage-html coverage
```

Then open `coverage/index.html` in your browser to view the report.

## Testing in a Laravel Application

If you want to test this package within a Laravel application:

1. Add the package to your Laravel application:
   ```bash
   composer require elshaden/popup-card --dev
   ```

2. Publish the configuration and migrations:
   ```bash
   php artisan vendor:publish --provider="Elshaden\PopupCard\CardServiceProvider"
   ```

3. Run the migrations:
   ```bash
   php artisan migrate
   ```

4. Test the package functionality in your application.

## Troubleshooting Common Testing Issues

- **Database Connection Issues**: Ensure your `.env` file has the correct database credentials for testing.
- **Nova Authentication**: Make sure you have a valid Nova license and authentication set up.
- **JavaScript Tests**: If you're testing the Vue components, ensure you have Node.js and npm installed.

## Writing New Tests

When adding new features or fixing bugs, please include appropriate tests:

- **Unit Tests**: For testing individual components in isolation
- **Feature Tests**: For testing API endpoints and integration
- **Browser Tests**: For testing the UI components (if applicable)

Follow the existing test patterns in the `tests` directory.

## Continuous Integration

This package uses GitHub Actions for continuous integration. Each pull request is automatically tested against multiple PHP versions and Laravel versions.

---

If you encounter any issues with testing, please open an issue on the GitHub repository.