<?php

namespace Gallery\Models\Album;

class AlbumMapper
{
    public function newAlbum($row)
    {
        return new AlbumEntity($row);
    }

    public function newAlbumCollection($rows)
    {
        $newAlbumCollection = [];
        foreach ($rows as $row) {
            $album = $this->newAlbum($row);
            $newAlbumCollection[$album->getTitle()] = $album;
        }

        return $newAlbumCollection;
    }
}
