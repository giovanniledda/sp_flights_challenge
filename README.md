<h1>Shippy Pro - Flights price challenge</h1>

<p align="center">
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## How to install the project

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

## The Solution

You can find proof of solution by launching the command  "php artisan test".
To be more specific, the algorithm that finds the best price on DB data set can be found at class "App\Services\FlightScanner", function generalMinPriceOptimizedSearch().
