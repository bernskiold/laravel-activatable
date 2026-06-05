<?php

namespace Bernskiold\LaravelActivatable\Tests\Models;

use Bernskiold\LaravelActivatable\Concerns\Activatable;
use Bernskiold\LaravelActivatable\Contracts\Activatable as ActivatableContract;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model implements ActivatableContract
{
    use Activatable, HasFactory;

    protected $guarded = [];
}
