<?php

namespace AdfabCms\Mapper;

interface BlockInterface
{
    public function findById($id);

    public function findByIdentifier($identifier);

    public function insert($block);

    public function update($block);
}
