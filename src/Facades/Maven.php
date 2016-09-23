<?php namespace Sukohi\Maven\Facades;

use Illuminate\Support\Facades\Facade;

class Maven extends Facade {

  protected static function getFacadeAccessor() {

    return 'maven';

  }

}