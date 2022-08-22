# Sql2Seeder

Convert sql files in your laravel 7 project to eloquent seeders

## Getting Started

### Intall package
```
composer require gadelgado/sql2seeder
```

### Add Service provides
```
$ providers = [
    ...

    /*
     * Package Service Providers...
     */
    Gadelgado\Sql2Seeder\Providers\Sql2SeederProvider::class,
    
    ...
];
```

### Run Command
```
php artisan sql:convert
```

# Licence
The MIT License (MIT). Please see [License File](./LICENSE.md) for more information.
