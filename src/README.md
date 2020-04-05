#Record Erros 404/500

### 404 and 500 Error records management system 
<br>
<article>
This library made to manage error records and display visits count , date times
ip and  more details about visits.
</article>

##### Step 1 
```
    composer require hayrullah/error-records
 ```

##### Step 2
 ```   
 php artisan vendor:publish "hayrullah/record-erros" --tag=config 
 php artisan vendor:publish "hayrullah/record-erros" --tag=views 
 ```

##### Step 3

<code>
    php artisan migrate
</code>   


 
