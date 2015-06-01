# Managing your Laravel 5 environment (.env)

This tool allows you to manage your ignored environment configuration when running a composer install / update or even manually call the command. It works when storing the .env.example file under the application root. Other keys are copied without change.

## Installation

Run the following command from your terminal:


 ```bash
 composer require "indragunawan/laravel-env-handler: 0.*"
 ```

or add this to require section in your composer.json file:

 ```
 "indragunawan/laravel-env-handler": "0.*"
 ```

then run ```composer update```

## Usage
via command line

```sh
$ php artisan env:update
```

or automatically check is there a new environment configuration after ```composer install``` or ```composer update```, add the following in your root composer.json file:

```json
{
    ...
    "scripts": {
        "post-install-cmd": [
            "php artisan env:update",
            ...
        ],
        "post-update-cmd": [
            "php artisan env:update",
            ...
        ]
    }
    ...
}
```

## Credits

This package is largely inspired by [this](https://github.com/Incenteev/ParameterHandler).
