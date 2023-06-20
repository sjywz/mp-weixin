<?php

namespace App\Admin\Renderable;

use App\Models\Mp;
use App\Models\Resource;
use Dcat\Admin\Grid;
use Dcat\Admin\Grid\LazyRenderable;

class ResourceTable extends LazyRenderable
{
    public function grid(): Grid
    {
        return Grid::make(new Resource(), function (Grid $grid) {
            $grid->column('id')->sortable();
            $grid->column('name', '名称');
            $grid->column('path', '图片')->image('',60);

            $grid->quickSearch(['id', 'name']);

            $grid->paginate(10);
            $grid->disableActions();

            $grid->filter(function (Grid\Filter $filter) {
                $filter->like('appid')->width(4);
                $filter->like('name')->width(4);
            });
        });
    }
}
