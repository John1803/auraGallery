<?php

namespace Gallery\Models\Album;

use Gallery\Models\AbstractMapper;

class AlbumMapper extends AbstractMapper
{
    /**
     * @param array $row Associative array of fetched album's row from DB
     * @return AlbumEntity
     */
    public function newEntity($row)
    {
        return new AlbumEntity($row);
    }
}
