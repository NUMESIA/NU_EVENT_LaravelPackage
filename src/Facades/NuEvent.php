<?php
namespace Numesia\NuEvent\Facades;

use Illuminate\Support\Facades\Facade;

class NuEvent extends Facade {
    protected static function getFacadeAccessor() { return 'NuEvent'; }
}
