# Testing Laravel Nova Popup Card

This document provides information on how to run tests for the Laravel Nova Popup Card package.

## Setup

Before running the tests, make sure you have installed the development dependencies:

```bash
composer install --dev
```

## Running Tests

You can run all tests using PHPUnit:

```bash
vendor/bin/phpunit
```

To run specific test suites:

```bash
# Run only unit tests
vendor/bin/phpunit --testsuite=Unit

# Run only feature tests
vendor/bin/phpunit --testsuite=Feature
```

To run a specific test file:

```bash
vendor/bin/phpunit tests/Unit/Models/PopupCardTest.php
```

## Test Coverage

To generate a test coverage report, you need to have Xdebug installed and enabled. Then run:

```bash
vendor/bin/phpunit --coverage-html coverage
```

This will generate an HTML coverage report in the `coverage` directory.

## Available Tests

### Unit Tests

Unit tests focus on testing individual components in isolation:

- **PopupCardTest**: Tests the PopupCard model methods and relationships
  - Tests the `scopeActive` method
  - Tests the `scopePublished` method
  - Tests the `getPopUp` method
  - Tests the relationship with users

### Feature Tests

Feature tests focus on testing the API endpoints and integration with Laravel:

- **PopupCardControllerTest**: Tests the PopupCardController endpoints
  - Tests retrieving popup card content when available
  - Tests behavior when popups are disabled in config
  - Tests behavior when a user has already seen a popup
  - Tests marking a popup as seen
  - Tests error handling when not authenticated

## Writing New Tests

When writing new tests, extend the `Elshaden\PopupCard\Tests\TestCase` class, which provides the necessary setup for testing the package:

```php
namespace Elshaden\PopupCard\Tests\Feature;

use Elshaden\PopupCard\Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class YourTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_does_something()
    {
        // Your test code here
    }
}
```

## Test Environment

The test environment is configured to use an in-memory SQLite database. The package migrations are automatically run before each test.

## Running Tests from a Project

If you've installed this package in your Laravel project and want to run its tests, follow these steps:

### Using PHPUnit

If your project uses PHPUnit for testing:

### 1. Navigate to the Package Directory

First, navigate to the package directory within your project's vendor folder:

```bash
cd vendor/elshaden/popup-card
```

### 2. Install Development Dependencies

The package's development dependencies (like PHPUnit and Orchestra Testbench) need to be installed:

```bash
composer install --dev
```

### 3. Run the Tests

Now you can run the tests using PHPUnit:

```bash
vendor/bin/phpunit
```

### 4. Alternative: Running Tests Without Navigating to the Package Directory

You can also run the package's tests directly from your project root:

```bash
vendor/bin/phpunit vendor/elshaden/popup-card/tests
```

### 5. Running Specific Test Suites or Files

To run specific test suites:

```bash
# From the package directory
vendor/bin/phpunit --testsuite=Unit

# From the project root
vendor/bin/phpunit vendor/elshaden/popup-card/tests/Unit
```

To run a specific test file:

```bash
# From the package directory
vendor/bin/phpunit tests/Unit/Models/PopupCardTest.php

# From the project root
vendor/bin/phpunit vendor/elshaden/popup-card/tests/Unit/Models/PopupCardTest.php
```

### Using Pest PHP

If your project uses Pest PHP for testing, you can still run the package's tests, but you'll need to use Pest's commands instead of PHPUnit's:

### 1. Navigate to the Package Directory

First, navigate to the package directory within your project's vendor folder:

```bash
cd vendor/elshaden/popup-card
```

### 2. Install Development Dependencies

The package's development dependencies need to be installed:

```bash
composer install --dev
```

### 3. Run the Tests with Pest

If your main project already has Pest installed, you can run the package's tests using Pest:

```bash
# From the package directory
../../../vendor/bin/pest

# From the project root
vendor/bin/pest vendor/elshaden/popup-card/tests
```

### 4. Running Specific Test Suites or Files with Pest

To run specific test suites:

```bash
# From the package directory
../../../vendor/bin/pest tests/Unit

# From the project root
vendor/bin/pest vendor/elshaden/popup-card/tests/Unit
```

To run a specific test file:

```bash
# From the package directory
../../../vendor/bin/pest tests/Unit/Models/PopupCardTest.php

# From the project root
vendor/bin/pest vendor/elshaden/popup-card/tests/Unit/Models/PopupCardTest.php
```

> **Note**: Pest is compatible with PHPUnit tests, so you can run PHPUnit tests using Pest without any modifications to the test files. However, if you want to fully migrate the package's tests to Pest's syntax, you would need to refactor the test files.

## Continuous Integration

It's recommended to set up continuous integration (CI) to run tests automatically on each push or pull request. You can use GitHub Actions, Travis CI, or any other CI service.

Example GitHub Actions workflow file (`.github/workflows/tests.yml`):

```yaml
name: Tests

on:
  push:
    branches: [ main ]
  pull_request:
    branches: [ main ]

jobs:
  tests:
    runs-on: ubuntu-latest

    strategy:
      matrix:
        php: [8.0, 8.1, 8.2]
        laravel: [9.*, 10.*]
        exclude:
          - php: 8.0
            laravel: 10.*

    name: PHP ${{ matrix.php }} - Laravel ${{ matrix.laravel }}

    steps:
      - name: Checkout code
        uses: actions/checkout@v3

      - name: Setup PHP
        uses: shivammathur/setup-php@v2
        with:
          php-version: ${{ matrix.php }}
          extensions: dom, curl, libxml, mbstring, zip, pcntl, pdo, sqlite, pdo_sqlite
          coverage: none

      - name: Install dependencies
        run: |
          composer require "laravel/framework:${{ matrix.laravel }}" --no-interaction --no-update
          composer update --prefer-dist --no-interaction --no-progress

      - name: Execute tests
        run: vendor/bin/phpunit
```
