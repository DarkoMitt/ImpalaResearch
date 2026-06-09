<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## About Laravel

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel takes the pain out of development by easing common tasks used in many web projects, such as:

- [Simple, fast routing engine](https://laravel.com/docs/routing).
- [Powerful dependency injection container](https://laravel.com/docs/container).
- Multiple back-ends for [session](https://laravel.com/docs/session) and [cache](https://laravel.com/docs/cache) storage.
- Expressive, intuitive [database ORM](https://laravel.com/docs/eloquent).
- Database agnostic [schema migrations](https://laravel.com/docs/migrations).
- [Robust background job processing](https://laravel.com/docs/queues).
- [Real-time event broadcasting](https://laravel.com/docs/broadcasting).

Laravel is accessible, powerful, and provides tools required for large, robust applications.

## Learning Laravel

Laravel has the most extensive and thorough [documentation](https://laravel.com/docs) and video tutorial library of all modern web application frameworks, making it a breeze to get started with the framework. You can also check out [Laravel Learn](https://laravel.com/learn), where you will be guided through building a modern Laravel application.

If you don't feel like reading, [Laracasts](https://laracasts.com) can help. Laracasts contains thousands of video tutorials on a range of topics including Laravel, modern PHP, unit testing, and JavaScript. Boost your skills by digging into our comprehensive video library.

## Laravel Sponsors

We would like to extend our thanks to the following sponsors for funding Laravel development. If you are interested in becoming a sponsor, please visit the [Laravel Partners program](https://partners.laravel.com).

### Premium Partners

- **[Vehikl](https://vehikl.com)**
- **[Tighten Co.](https://tighten.co)**
- **[Kirschbaum Development Group](https://kirschbaumdevelopment.com)**
- **[64 Robots](https://64robots.com)**
- **[Curotec](https://www.curotec.com/services/technologies/laravel)**
- **[DevSquad](https://devsquad.com/hire-laravel-developers)**
- **[Redberry](https://redberry.international/laravel-development)**
- **[Active Logic](https://activelogic.com)**

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).


# Impala Research Website Redesign

## Overview

This project is a redesign and frontend implementation of the Impala Research website based on the provided Figma design.

The goal of the project is to modernize the existing website, improve the user experience, and prepare the platform for future backend integrations.

---

## Technology Stack

* Laravel 12
* Blade Templates
* Tailwind CSS
* Alpine.js

---

## Implemented Features

### Home Page

* Responsive navigation
* Hero section
* Impala Infinity Study Builder
* Goal selection
* Research method selection
* Audience selection
* Study details form
* Study review page
* Request submission flow
* Dynamic study summary
* Footer section

### Contact Page

* Responsive contact form
* Form validation
* Country selection dropdown
* Demo request simulation

### Authentication Pages

* Register page
* Login page
* Client-side validation
* Password requirements validation
* Email validation

### Footer

* Navigation links
* Social links placeholders
* Email subscription form
* Subscription validation

---

## Current Scope

This version focuses on frontend implementation and user experience.

The following features are currently implemented as prototypes:

* User registration
* User login
* Demo scheduling
* Study request submission
* Email subscription

Database integration and backend functionality can be connected in future development phases.

---

## Installation

Clone the repository:

```bash
git clone <repository-url>
```

Install PHP dependencies:

```bash
composer install
```

Install Node dependencies:

```bash
npm install
```

Create environment file:

```bash
cp .env.example .env
```

Generate application key:

```bash
php artisan key:generate
```

Run the application:

```bash
php artisan serve
npm run dev
```

Application will be available at:

```text
http://127.0.0.1:8000
```

---

## Notes

This implementation follows the provided Figma design and serves as a frontend foundation for future platform development.

Additional features such as:

* User management
* Admin panel
* Authentication backend
* Database storage
* Email notifications
* Content management

can be integrated in future project phases.

---

## Developed By

Darko Mitovski

Software Engineering Student
Brainster Next College

