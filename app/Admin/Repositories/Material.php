<?php

namespace App\Admin\Repositories;

use App\Models\Material as Model;
use Dcat\Admin\Repositories\EloquentRepository;

class Material extends EloquentRepository
{
    /**
     * Model.
     *
     * @var string
     */
    protected $eloquentClass = Model::class;
}
