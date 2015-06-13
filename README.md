# Managing your Laravel 5 environment (.env)

This package allows you to manage your ignored environment configuration when you are running a composer install / update or even manually call the command. It works when storing the .env.example file under the application root. Other keys are copied without change.

## Installation

Run the following command from your terminal:


 ```bash
 composer require "indragunawan/laravel-env-handler: 0.*"
 ```

or add this to require section in your composer.json file:

 ``` json
{
    "require": {
        "indragunawan/laravel-env-handler": "0.*"
    }
}
```

run ```composer update```

Then add Service provider to `config/app.php`

``` php
    'providers' => [
        // ...
        'IndraGunawan\LaravelEnvHandler\EnvHandlerServiceProvider'
    ]
```

## Usage
via command line

```sh
$ php artisan env:update
```


### Options
```sh
$ php artisan env:update --force
```
execute the update even though there are no new environment parameters

## Credits

This package is largely inspired by [this](https://github.com/Incenteev/ParameterHandler).
