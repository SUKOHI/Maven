# Maven
A Laravel package to manage FAQ.  
(This package is for Laravel 5.2+)

!['Example'](http://i.imgur.com/eQpsIte.png)

# Requirements

* [LaravelCollective/html](https://github.com/LaravelCollective/html)
* [sukohi/cahen](https://github.com/SUKOHI/Cahen)

# Installation

Add this package name in composer.json

    "require": {
      "sukohi/maven": "1.*"
    }

Execute composer command.

    composer update

Register the service provider in app.php

    'providers' => [
        ...Others...,
        Collective\Html\HtmlServiceProvider::class,
        Sukohi\Cahen\CahenServiceProvider::class,
        Sukohi\Maven\MavenServiceProvider::class,
    ]

Also alias

    'aliases' => [
        ...Others...,
        'Form' => Collective\Html\FormFacade::class,
        'Html' => Collective\Html\HtmlFacade::class,
        'Cahen'   => Sukohi\Cahen\Facades\Cahen::class,
        'Maven'   => Sukohi\Maven\Facades\Maven::class,
    ]

And execute the next commands.  

    php artisan vendor:publish
    php artisan migrate

# Usage(Management page)

Set a route in your routes.php.

    Route::match(['get', 'post'], 'maven', function () {
    
        return \Maven::view();
        
    });

Now you can access management page that Maven provides like http://YOUR-DOMAIN/maven.  
Note: I believe that you need to authenticate in routes.php before calling `Maven::view()`.

# Usage

(in Controller)

    $faqs = \Maven::get();
    
    // or
 
    $faqs = \Maven::tag('YOUR-TAG')->get();
    $faqs = \Maven::tag(['YOUR-TAG-1', 'YOUR-TAG-2'])->get();
    
    // or
    
    $faqs = \Maven::locale('en')->get();
    $faqs = \Maven::locale(['en', 'es'])->get();
    
    return view('YOUR-VIEW', ['faqs' => $faqs]);

(in View)

    <!-- Data -->

    @foreach($faqs as $faq)
        {!! $faq->question !!}<br><br>
        {!! $faq->answer !!}<br><br>
        Tag: {!! implode(',', $faq->tags) !!}
        <hr>
    @endforeach
    
    <!-- Pager -->
    
    {!! $faqs->links() !!}

# License

This package is licensed under the MIT License.

Copyright 2016 Sukohi Kuhoh