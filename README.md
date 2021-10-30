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

This package comes with a command that creates the repositories and contracts.

```shell
php artisan make:repository PostRepository
```

This command will create a repository file, a contract file and links the in the RespositoryServiceProvider.
PS: the used model will be the first word of the Repository file name (ex : PostRepository will be linked to the model Post)

In case the model does not exist you can use this command 

```shell
php artisan make:repository PostRepository -a
```

This command will create the repository as well as execute a model creation command

```shell
php artisan make:model Post -all
```

Or if you do not want to create everything you can specify the option.

```shell
php artisan make:repository PostRepository -m -s -f -r
```
