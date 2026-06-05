<?php

namespace Bernskiold\LaravelActivatable\Tests\Models;

use Bernskiold\LaravelActivatable\Concerns\Activatable;
use Illuminate\Database\Eloquent\Model;

class Widget extends Model
{
    use Activatable;

    public const ACTIVE_COLUMN = 'is_enabled';

    protected $guarded = [];
}
