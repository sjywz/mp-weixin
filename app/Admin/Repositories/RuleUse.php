<?php

namespace App\Admin\Repositories;

use App\Models\RuleUse as Model;
use Dcat\Admin\Repositories\EloquentRepository;

class RuleUse extends EloquentRepository
{
    /**
     * Model.
     *
     * @var string
     */
    protected $eloquentClass = Model::class;
}
