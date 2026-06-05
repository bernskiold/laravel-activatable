<?php

namespace Bernskiold\LaravelActivatable\Events;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ModelActivated
{
    use Dispatchable, SerializesModels;

    public function __construct(public Model $model) {}
}
