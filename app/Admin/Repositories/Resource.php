<?php

namespace App\Admin\Repositories;

use App\Models\Resource as Model;
use Dcat\Admin\Repositories\EloquentRepository;

class Resource extends EloquentRepository
{
    /**
     * Model.
     *
     * @var string
     */
    protected $eloquentClass = Model::class;
}
