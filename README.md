# Maven
A Laravel package to manage FAQ.  
(This package is for Laravel 5.2+)

***[Demo](http://demo-laravel52.capilano-fw.com/maven)***

!['Example'](http://i.imgur.com/uMsZAxp.png)

# Requirements

* [LaravelCollective/html](https://github.com/LaravelCollective/html)
* [sukohi/cahen](https://github.com/SUKOHI/Cahen)
* [sukohi/smoothness](https://github.com/SUKOHI/Smoothness)
* [sukohi/neatness](https://github.com/SUKOHI/Neatness)

# Installation

Execute the next command.

    composer require sukohi/maven:3.*

Set the service providers in app.php

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

Then execute the next commands.  

    php artisan vendor:publish
    php artisan migrate

# Usage (Management page)

Set `Maven::route()` in your `routes.php`.

    \Sukohi\Maven\Maven::route();
    
    // or with default locale
    
    \Sukohi\Maven\Maven::route('en');

Now you can access to `http(s)://YOUR-DOMAIN/admin/maven. `   
Note: I believe that you need to authenticate in routes.php before calling `Maven::route()` using middleware or something.

[Config]  
After publishing, you should have `maven.php` in your config folder.  
you can set some values in the file like the followings.

> locales

First of all, you have only `en` locale.  
You can add more locales if you want like so.  

    
    'locales' => [
        'en' => 'English',
        'ja' => 'Japanese',
        'es' => 'Spanish'
    ],

    // or

    'locales' => [
        'ja' => '日本語',
        'en' => '英語',
        'es' => 'スペイン語'
    ],
    
    // The keys and values refer locale symbols and language names.

(URI)  
The default value is `admin/maven`.  
So `http(s)://YOUR-DOMAIN/admin/maven` is the URL for managing FAQs.
    
(Per Page)  
You can change maximum records per page.

# Usage (Retrieve data)

[Basic Way]  

    $faqs = \Maven::get();
    
    foreach ($faqs as $faq) {

        if($faq->hasLocale('en')) {

            // FAQ
            echo $faq->getQuestion('en');
            echo $faq->getRawQuestion('en');
            echo $faq->getAnswer('en');
            echo $faq->getRawAnswer('en');
            
            // Tag(s)
            $tags = $faq->getTags('en');    // Collection
            
            // Others
            echo $faq->sort_id;
            echo $faq->unique_key;
            echo $faq->created_at;
            echo $faq->updated_at;
            
        }

    }
    
[Filtering]

(Tag(s))  
    
    $faqs = \Maven::tag('YOUR-TAG')->get();
    $faqs = \Maven::tag(['YOUR-TAG-1', 'YOUR-TAG-2'])->get();
    

(Unique Key)
    
    $faq = \Maven::uniqueKey('952557a09ef19aae1d9e2a276db18a66')->first();
    
    // or 
    
    $faqs = \Maven::uniqueKey([
        '952557a09ef19aae1d9e2a276db18a66', 
        'fashrtrhstgrfaeargthukfyhdredeff', 
    ])->get();
    
    
(Pagination)
    
    {!! $faqs->links() !!}
    
(All Tag(s))
    
    $tags = \Maven::getAllTags();
    
    // or with $draft_filter_flag
    
    $tags = \Maven::getAllTags($draft_filter_flag = true);
    
In this case, if you'd like to get `en` tag(s), you should call like so.
    
    $english_tags = $tags['en'];
    
# Model Instance

In order to get model instance of this package, you can use `getModel()`.

    \Maven::getModel('unique_key');
    \Maven::getModel('faq');
    \Maven::getModel('tag');

# Export/Import

You can two commands for export/import.

[Export]  

    php artisan maven:export

* Exported file is located at `storage/app/maven/maven_faqs.json`, `storage/app/maven/maven_tags.json` and `storage/app/maven/maven_unique_keys.json`.
    
[Import]  

    php artisan maven:import


# License

This package is licensed under the MIT License.  
Copyright 2016 Sukohi Kuhoh