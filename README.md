<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## About Laravel News API

This projects consist of providing an API that aggregates news results from 3rd party APIS and returns them on a mapped result.

This API Provides with user Login; Registration and Update.

## How To Run
By default, the application will run on port `8000`.</br>
To use a different port modify `docker-compose.yml` file.</br>

`cd php-crud-live-main` </br>
`docker-compose up` </br>
`http://localhost:8000/api` is exposed.

## APIs that are included

`POST http://0.0.0.0:8000/api/user/register` => Create User</br>
`POST http://0.0.0.0:8000/api/user/login` => Login User</br>
`GET http://0.0.0.0:8000/api/user` => Get User Details</br>
`PUT http://0.0.0.0:8000/api/user` => Update User</br>
`GET http://0.0.0.0:8000/api/user/news` => Get News for this user</br>

Postman File Included at `public/PERSONAL.postman_collection.json`



