<?php

namespace Gallery\Models\Image;

use Gallery\Models\AbstractMapper;

class ImageMapper extends AbstractMapper
{
    public function newEntity($row)
    {
        return new ImageEntity($row);
    }
}
