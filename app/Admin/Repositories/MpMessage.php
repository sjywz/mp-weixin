<?php

namespace App\Admin\Repositories;

use App\Models\MpMessage as Model;
use Dcat\Admin\Repositories\EloquentRepository;

class MpMessage extends EloquentRepository
{
    /**
     * Model.
     *
     * @var string
     */
    protected $eloquentClass = Model::class;
}
