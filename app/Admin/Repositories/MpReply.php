<?php

namespace App\Admin\Repositories;

use App\Models\MpReply as Model;
use Dcat\Admin\Repositories\EloquentRepository;

class MpReply extends EloquentRepository
{
    /**
     * Model.
     *
     * @var string
     */
    protected $eloquentClass = Model::class;
}
