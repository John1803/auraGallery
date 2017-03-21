<?php

namespace Gallery\Models\Album;

use Gallery\Models\AbstractModel;

class ImageModel extends AbstractModel
{
    public function fetchImagesOfAlbum($id)
    {
        $images = $this->pdo->fetchAll("SELECT img.id, 
                                                img.album_id, 
                                                img.title, 
                                                img.path, 
                                                img.size, 
                                                img.mediaType
                                        FROM images AS img
                                        JOIN albums AS descendant
                                          ON descendant.id = img.album_id
                                          AND descendant.id = :id;",
                                        ["id" => $id, ]
        );

        return $images;
    }
}