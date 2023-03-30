<?php

namespace App\Admin\Repositories;

use App\Models\Mp as Model;
use Dcat\Admin\Repositories\EloquentRepository;

class Mp extends EloquentRepository
{
    /**
     * Model.
     *
     * @var string
     */
    protected $eloquentClass = Model::class;
}
