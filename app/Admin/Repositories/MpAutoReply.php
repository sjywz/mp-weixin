<?php

namespace App\Admin\Repositories;

use App\Models\MpAutoReply as Model;
use Dcat\Admin\Repositories\EloquentRepository;

class MpAutoReply extends EloquentRepository
{
    /**
     * Model.
     *
     * @var string
     */
    protected $eloquentClass = Model::class;
}
