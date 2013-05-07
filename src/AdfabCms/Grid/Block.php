<?php

namespace AdfabCms\Grid;

use AtDataGrid\DataGrid;

class Block extends DataGrid\DataGrid
{
    public function init()
    {
        parent::init();

        $this->setCaption('Manage blocks')
             ->setIdentifierColumnName('block_id');
    }
}
