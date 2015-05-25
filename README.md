# Telekinesis

Universal REST controller with Eloquent-like calls in javascript for retrieving data from server.

## Installation

 * install package with ```composer require ont/laravel-telekinesis```
 * add service provider to ```config/app.php```
    ```php
    <?php
        'providers' => array(
            ...
            'Ont\Telekinesis\ServiceProvider',
            ...
        ),
    ?>
    ```
 * publish package assets via ```php artisan vendor:publish```

## Usage
Because of CSRF protection enabled by default in laravel5 we need special ```meta``` tag in layout wich will use telekinesis:
```html
<head>
    ...
    <meta name="csrf_token" content="{!! csrf_token() !!}"/>
    ...
</head>
```
Include jquery and telekinesis.js at the bottom of the page:
```html
    ...
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
    <script src="{{asset('ont/telekinesis/telekinesis.js')}}" type="text/javascript" charset="utf-8"></script>
</body>
```

Finally you can request data in js via usual eloquent calls:
```js
T('\\App\\Resume').whereHas('vacancies', function(q){
    q.where('views', '>', 100); 
}).get(function(resumes){
    console.log(resumes);
});
```
Here we request server to return such resumes which have related vacancies with "views" field greater than 100.
Function ```get()``` do async call to server and accept callback as paramenter.


Enjoy eloquent in javascript!
