<?php

namespace Bernskiold\LaravelActivatable\Tests\Models;

use Bernskiold\LaravelActivatable\Concerns\Activatable;
use Illuminate\Database\Eloquent\Model;

class Page extends Model
{
    use Activatable;

    protected $guarded = [];

    protected bool $tracksInactivatedAt = true;
}
