#Record Erros 404/500

###404 and 500 Error records management system 
<br>
<article>
This library made to manage error records and display visits count , date times
ip and  more details about visits.
</article>

#####Step 1 
<code>
    composer require hayrullah/error-records
</code>

#####Step 2
<code>
    php artisan vendor:publish "hayrullah/record-erros" --tag=config
</code> 
  
<br>

<code>
    php artisan vendor:publish "hayrullah/record-erros" --tag=views
</code>  
 
<br>

<code>
    php artisan vendor:publish "hayrullah/record-erros" --tag=routes
</code>   

#####Step 3

<code>
    php artisan migrate
</code>   


 
