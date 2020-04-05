# Laravel Errors Management System 


[![Latest Version on Packagist](https://img.shields.io/packagist/v/hayrullah/laravel-errors-management.svg?style=flat-square)](https://packagist.org/packages/hayrullah/laravel-errors-management)
![](https://github.com/hayrullah/laravel-errors-management/workflows/Run%20Tests/badge.svg?branch=master)
[![Total Downloads](https://img.shields.io/packagist/dt/hayrullah/laravel-errors-management.svg?style=flat-square)](https://packagist.org/packages/hayrullah/laravel-errors-management)

### 404 and 500 error records  

<article>
This library made to manage error records and display visits count , date times
ip and  more details about visits.
</article>


## Documentation, Installation, and Usage Instructions

See the [DOCUMENTATION](https://packagest.org/laravel-errors-management/) for detailed installation and usage instructions.

##### Step 1 
```
    composer require hayrullah/laravel-errors-management
 ```

##### Step 2
 ```   
 
 php artisan vendor:publish "hayrullah/record-erros" --tag=views 
 ```

##### Step 3
 ```   
 php artisan vendor:publish "hayrullah\RecordErrors\RecordErrorServiceProvider" --tag=config 
 php artisan vendor:publish "hayrullah\RecordErrors\RecordErrorServiceProvider" --tag=views 
 ```


##### Step 4

<code>
    php artisan migrate
</code>   


 
