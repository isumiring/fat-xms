<?php

namespace App\Support\Facades;

use Illuminate\Support\Facades\Facade;

class FatLibFacade extends Facade 
{
    protected static function getFacadeAccessor() 
    { 
    	return 'fat'; 
	}
}