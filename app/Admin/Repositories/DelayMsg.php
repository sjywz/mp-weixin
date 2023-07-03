<?php

namespace App\Admin\Repositories;

use App\Models\DelayMsg as Model;
use Dcat\Admin\Repositories\EloquentRepository;

class DelayMsg extends EloquentRepository
{
    /**
     * Model.
     *
     * @var string
     */
    protected $eloquentClass = Model::class;
}
