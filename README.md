<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://travis-ci.org/laravel/framework"><img src="https://travis-ci.org/laravel/framework.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

# Laravel Roles Permissions - Spatie version

## This project was created in October, 2022 as Laravel 9.34.0 version
---
This is a Laravel 9 adminpanel starter project with roles-permissions management based on Spatie Laravel-permission package and AdminLTE.

## Build with
* Laravel
* Bootstrap
* jQuery

## Installation
This is not a package - it's a full Laravel project that you should use as a starter boilerplate, and then add your own custom functionality.
1. Clone the repository with `git clone` or download this project
2. Copy `.env.example` file to `.env` and edit database credentials there
3. Run `composer install`
4. Run `php artisan key:generate`
5. Run `php artisan migrate --seed` (it has some seeded data - see below)
6. Go to Permission.php at `vendor/spatie/laravel-permission/src/Models` and copy this code after function roles

    ``` php
    public function labelPermissions(): BelongsToMany
    {
        return $this->belongsToMany(LabelPermission::class, 'group_permissions')->withTimestamps();
    }
    ``` 
7. and run your laravel `php artisan serve`
8. Enjoyed!

## Default Credentials 
1. Super Admin

    ```
    Email: superadmin@mail.com
    Password: password
    ```
2. Admin

    ```
    Email: admin@mail.com
    Password: password
    ```
3. User

    ```
    Email: user@mail.com
    Password: password
    ```
