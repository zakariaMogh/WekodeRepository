//////////////////
# WekodeRepository

This is a package to easely integrate a repository pattern with the needed service provider and all the necessary basic functions


## Installation

Require this package with composer. It is recommended to only require the package for development.

```shell
composer require wekode/repository
```

Laravel uses Package Auto-Discovery, so doesn't require you to manually add the ServiceProvider.


### Laravel without auto-discovery:

If you don't use auto-discovery, add the ServiceProvider to the providers array in config/app.php

```php
Wekode\Repository\RepositorySetupServiceProvider,
```

#### Copy the package main files and setup your repository with the publish command:

```shell
php artisan vendor:publish --provider="Wekode\Repository\RepositorySetupServiceProvider"
```

## Usage

// TODO

