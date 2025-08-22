# WARP.md

This file provides guidance to WARP (warp.dev) when working with code in this repository.

Project overview
- Stack: Laravel 12 (PHP 8.2), Pest for tests, Laravel Dusk for browser tests, Vite + Tailwind CSS for assets.
- Package managers: Composer (PHP) and npm (JS/CSS tooling).
- Default DB for the app is SQLite; tests are configured to run against MySQL (see phpunit.xml).

Common development commands
Setup
- Install dependencies:
  ```sh
  composer install
  npm install
  ```
- Create environment and app key (if not present):
  ```sh
  cp -n .env.example .env || true
  php artisan key:generate
  ```
- Database (choose one):
  - SQLite (simple local dev):
    ```sh
    touch database/database.sqlite
    # Update .env:
    #   DB_CONNECTION=sqlite
    #   DB_DATABASE="/absolute/path/to/repo/database/database.sqlite"
    php artisan migrate
    ```
  - MySQL (matches CI and test config):
    ```sh
    # Ensure a local MySQL server is running and create the DB once
    mysql -uroot -e 'CREATE DATABASE IF NOT EXISTS xptrackr_development;'
    # Update .env accordingly (DB_*), then migrate
    php artisan migrate
    ```

Dev loop (app server, queue worker, logs, and asset dev server)
- Run the integrated dev script (spawns: PHP dev server, queue worker, Laravel Pail logs, Vite dev):
  ```sh
  composer run dev
  ```
- Alternatively, run pieces manually in separate panes:
  ```sh
  php artisan serve
  php artisan queue:listen --tries=1
  php artisan pail --timeout=0
  npm run dev
  ```

Build assets
```sh
npm run build
```

Lint/format PHP (Laravel Pint)
- Dry-run (show changes without writing):
  ```sh
  ./vendor/bin/pint --test
  ```
- Apply formatting:
  ```sh
  ./vendor/bin/pint
  ```

Tests (Pest)
- Run all unit/feature tests:
  ```sh
  composer test
  # or
  ./vendor/bin/pest
  ```
- Run a single test file:
  ```sh
  ./vendor/bin/pest tests/Unit/UserTest.php
  ```
- Filter by test name (substring match):
  ```sh
  ./vendor/bin/pest --filter "user can be created"
  ```
- Increase verbosity when debugging:
  ```sh
  ./vendor/bin/pest -vvv
  ```

Browser tests (Laravel Dusk)
- Prereqs: Google Chrome installed; Chromedriver handled automatically (see DuskTestCase). If driver mismatch occurs:
  ```sh
  php artisan dusk:chrome-driver --detect
  ```
- Start the app server (if not using the integrated dev script):
  ```sh
  php artisan serve --host=127.0.0.1 --port=8000
  ```
- Run Dusk tests (uses .env.dusk.local if present):
  ```sh
  php artisan dusk
  ```
- Run a single Dusk test by name:
  ```sh
  php artisan dusk --filter "can visit homepage"
  ```

Database notes
- Application default DB connection (config/database.php) is SQLite; .env controls runtime DB.
- Test environment (phpunit.xml) forces MySQL with database xptrackr_test. Ensure a local MySQL server is available, or adjust phpunit.xml for local-only workflows.
- Tests use RefreshDatabase or DatabaseMigrations to run migrations automatically.

High-level architecture
- Bootstrap and routing
  - bootstrap/app.php configures the Laravel Application, registers:
    - Web routes: routes/web.php
    - Console commands: routes/console.php
    - Health check endpoint at /up
  - routes/web.php: root route (/) behind guest middleware returns resources/views/welcome.blade.php.

- HTTP layer and views
  - Controllers live in app/Http/Controllers (currently minimal base Controller).
  - Blade views in resources/views; Vite is used to include and hot-reload assets during development.

- Assets (Vite + Tailwind)
  - Vite entrypoints: resources/css/app.css and resources/js/app.js (vite.config.js).
  - Tailwind CSS is wired via @tailwindcss/vite and processed by Vite.

- Domain and persistence
  - Eloquent models in app/Models. User extends Authenticatable and is configured for “magic links” style flows via limited fillable fields (username, email).
  - Migrations in database/migrations. Notable: 2025_08_22_145724_modify_users_table_for_magic_links.php modifies the users table for magic links.

- Queues and logs
  - composer run dev includes php artisan queue:listen and php artisan pail for live queue processing and log viewing during development.

- Testing strategy
  - Pest is configured in tests/Pest.php to use:
    - Tests\TestCase + RefreshDatabase for Feature and Unit tests
    - Tests\DuskTestCase + DatabaseMigrations for Browser tests
  - DuskTestCase starts a Chromedriver on port 9515 (headless by default) and customizes Chrome options for CI and local runs.

CI hints (to reproduce locally)
- GitHub Actions (.github/workflows/tests.yml) runs MySQL 8, executes Pest, then launches the app and runs Dusk.
- To mirror locally:
  - Ensure MySQL is running and a test DB exists (xptrackr_test) matching phpunit.xml.
  - Start the dev server (php artisan serve) before php artisan dusk when running browser tests.

