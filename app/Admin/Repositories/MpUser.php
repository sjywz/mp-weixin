<?php

namespace App\Admin\Repositories;

use App\Models\MpUser as Model;
use Dcat\Admin\Repositories\EloquentRepository;

class MpUser extends EloquentRepository
{
    /**
     * Model.
     *
     * @var string
     */
    protected $eloquentClass = Model::class;
}
