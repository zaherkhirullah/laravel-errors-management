# Laravel Errors Management System 


[![Latest Version on Packagist](https://img.shields.io/packagist/v/hayrullah/laravel-errors-management.svg?style=flat-square)](https://packagist.org/packages/hayrullah/laravel-errors-management)
![](https://github.com/hayrullah/laravel-errors-management/workflows/Run%20Tests/badge.svg?branch=master)
[![Total Downloads](https://img.shields.io/packagist/dt/hayrullah/laravel-errors-management.svg?style=flat-square)](https://packagist.org/packages/hayrullah/laravel-errors-management)
[![GitHub issues](https://img.shields.io/github/issues/zaherkhirullah/laravel-errors-management)](https://github.com/zaherkhirullah/laravel-errors-management/issues)
[![GitHub forks](https://img.shields.io/github/forks/zaherkhirullah/laravel-errors-management)](https://github.com/zaherkhirullah/laravel-errors-management/network)
[![GitHub stars](https://img.shields.io/github/stars/zaherkhirullah/laravel-errors-management)](https://github.com/zaherkhirullah/laravel-errors-management/stargazers)
[![GitHub license](https://img.shields.io/github/license/zaherkhirullah/laravel-errors-management)](https://github.com/zaherkhirullah/laravel-errors-management)
[![Quality Score](https://img.shields.io/scrutinizer/g/zaherkhirullah/laravel-errors-management.svg?style=flat-square)](https://scrutinizer-ci.com/g/zaherkhirullah/laravel-errors-management)
[![StyleCI](https://styleci.io/repos/253813301/shield)](https://styleci.io/repos/253813301)

### 404 and 500 error records  

<article>
This library made to manage error records and display visits count , date times
ip and  more details about visits.
</article>


## Documentation, Installation, and Usage Instructions

See the [DOCUMENTATION](https://packagist.org/packages/hayrullah/laravel-errors-management) for detailed installation and usage instructions.

<i> Step 1 </i>

```
$ composer require hayrullah/laravel-errors-management
 ```

<i> Step 2 </i>

```   
$ php artisan adminlte:install  --tag=main_views
```

<i> Step 3 </i>

```   
$ php artisan vendor:publish --provider="Hayrullah\ErrorsManagement\ErrorsManagementProvider" --tag=config
$ php artisan vendor:publish --provider="Hayrullah\ErrorsManagement\ErrorsManagementProvider" --tag=views
```

<i> Step 4 </i>
   
```   
$  php artisan migrate
```
```   
$  php artisan serve
```


 
