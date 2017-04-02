<?php

namespace Gallery\Models\Image;

use Gallery\Models\AbstractModel;

class ImageModel extends AbstractModel
{
    /**
     * @param $id
     * @return array Associative array of images
     */
    public function fetchImagesOfAlbum($id)
    {
        $images = $this->pdo->fetchAll(
            'SELECT img.id, img.album_id, img.title, img.path, img.size, img.mediaType
            FROM images AS img
            JOIN albums AS descendant
            ON descendant.id = img.album_id
             AND descendant.id = :id;',
            ['id' => $id, ]
        );

        return $images;
    }

    /**
     * @param array $data
     */
    public function create(array $data)
    {
        $this->pdo->perform(
            'INSERT INTO images(album_id, title, path, size, mediaType) 
            VALUES(:album_id, :title, :path, :size, :mediaType);',
            $data
        );
    }
}