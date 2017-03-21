<?php

namespace Gallery\Models\Album;

use Gallery\Models\AbstractMapper;

class AlbumMapper extends AbstractMapper
{
    public function newEntity($row)
    {
        return new AlbumEntity($row);
    }
}
