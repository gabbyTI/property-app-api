<?php

namespace App\Repositories\Eloquent;

use App\Models\Property;
use App\Repositories\Contracts\IProperty;

class PropertyRepository extends BaseRepository implements IProperty
{
    public function model()
    {
        return Property::class;
    }
}
