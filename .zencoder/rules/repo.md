---
description: Repository Information Overview
alwaysApply: true
---

# Amin Talent Solutions Website Information

## Summary
This repository contains a Laravel-based dashboard application for Amin Talent Solutions, a talent management platform. The application manages freelancers, companies, job requests, assignments, and deliverables through a web interface.

## Structure
- **amin-dashboard/**: Laravel application for talent management
  - **app/**: Core application code with Models, Controllers, and Providers
  - **bootstrap/**: Application bootstrap files
  - **config/**: Configuration files for Laravel services
  - **database/**: Database migrations, seeders, and factories
  - **public/**: Publicly accessible files and entry point
  - **resources/**: Frontend assets (CSS, JS, Views)
  - **routes/**: API and web route definitions
  - **storage/**: Application storage for logs, cache, etc.
  - **tests/**: PHPUnit test files
  - **vendor/**: Composer dependencies

## Language & Runtime
**Language**: PHP
**Version**: ^8.2
**Framework**: Laravel 12.x
**Build System**: Composer + Vite
**Package Manager**: Composer (PHP), NPM (JavaScript)

## Dependencies
**Main Dependencies**:
- laravel/framework: ^12.0
- laravel/sanctum: ^4.2 (API authentication)
- laravel/tinker: ^2.10.1 (REPL for Laravel)

**Development Dependencies**:
- fakerphp/faker: ^1.23 (Test data generation)
- laravel/pail: ^1.2.2 (Log viewer)
- laravel/pint: ^1.24 (PHP code style fixer)
- laravel/sail: ^1.41 (Docker development environment)
- phpunit/phpunit: ^11.5.3 (Testing framework)
- tailwindcss: ^4.0.0 (CSS framework)
- vite: ^7.0.4 (Frontend build tool)

## Build & Installation
```bash
# Install PHP dependencies
composer install

# Install JavaScript dependencies
npm install

# Set up environment
cp .env.example .env
php artisan key:generate

# Run database migrations
php artisan migrate

# Build frontend assets
npm run build
```

## Development
```bash
# Start development server with hot reloading
composer dev

# Alternative: Run components separately
php artisan serve
npm run dev
```

## Docker
**Configuration**: Laravel Sail is included for Docker-based development
**Setup Command**:
```bash
# Start Docker environment
./vendor/bin/sail up
```

## Testing
**Framework**: PHPUnit
**Test Location**: tests/ directory with Feature/ and Unit/ subdirectories
**Configuration**: phpunit.xml in project root
**Run Command**:
```bash
composer test
# or
php artisan test
```

## Database
**Default**: SQLite (for development)
**Models**:
- User: Base authentication model
- Admin: Administrative users
- Company: Client companies
- Freelancer: Talent providers
- JobRequest: Work requests from companies
- Assignment: Assigned work to freelancers
- Deliverable: Work products
- StatusUpdate: Progress updates on assignments