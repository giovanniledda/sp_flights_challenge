<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## How to install

<ul>
    <li>clone the repo on a local dir</li>
    <li>compile your .env file</li>
    <li>launch "composer install"</li>
    <li>launch "php artisan migrate:fresh --seed"</li>
    <li>launch "php artisan serve"</li>
</ul>

...and it's done!

## The Challenge

<p>

Given:

TABLE 1:

<ul>
    <li>- id</li>
    <li>- name</li>
    <li>- code</li>
    <li>- lat</li>
    <li>- lng</li>
</ul>

TABLE 2:

<ul>
    <li>- code_departure</li>
    <li>- code_arrival</li>
    <li>- price</li>
</ul>
</p>


Try to create a PHP algorithm
that finds the lowest price, given
two different airport's code in
table 1, assuming at most 2
stopovers!

<hr />
The result is represented in a Blade Landing Page.


- [Visit Flights homepage](https://laravel.com/docs/routing).

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
