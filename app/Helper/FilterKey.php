<?php

namespace App\Helper;

use App\Ttl;

class FilterKey
{
    public function filterKeyValue($key_values)
    {
        $ttl = Ttl::first()->ttl;
    }
}
