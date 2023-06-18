<?php

namespace App\Admin\Renderable;

use App\Models\MpReply;
use Dcat\Admin\Grid;
use Dcat\Admin\Grid\LazyRenderable;

class ReplyTable extends LazyRenderable
{
    public function grid(): Grid
    {
        return Grid::make(new MpReply(), function (Grid $grid) {
            $grid->column('id')->sortable();
            $grid->column('title');
            $grid->column('type')->using(MpReply::$type)->label();
            $grid->column('wight');

            $grid->quickSearch(['id', 'title']);

            $grid->paginate(10);
            $grid->disableActions();

            $grid->filter(function (Grid\Filter $filter) {
                $filter->like('title')->width(4);
            });
        });
    }
}
