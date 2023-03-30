<?php

namespace App\Admin\Repositories;

use App\Models\PlatformEvent as Model;
use Dcat\Admin\Repositories\EloquentRepository;

class PlatformEvent extends EloquentRepository
{
    /**
     * Model.
     *
     * @var string
     */
    protected $eloquentClass = Model::class;
}
