<?php

namespace App\Admin\Renderable;

use App\Models\Mp;
use Dcat\Admin\Grid;
use Dcat\Admin\Grid\LazyRenderable;

class MpTable extends LazyRenderable
{
    public function grid(): Grid
    {
        return Grid::make(new Mp(), function (Grid $grid) {
            $grid->column('id')->sortable();
            $grid->column('name', '名称');
            $grid->column('appid', 'AppId');
            $grid->column('plat_appid', '平台ID');

            $grid->quickSearch(['id', 'name', 'appid']);

            $grid->paginate(10);
            $grid->disableActions();

            $grid->filter(function (Grid\Filter $filter) {
                $filter->like('appid')->width(4);
                $filter->like('name')->width(4);
            });
        });
    }
}
