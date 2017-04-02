<?php

namespace Gallery\Models\Image;

use Gallery\Models\Album\AlbumEntity;
use Zend\Diactoros\UploadedFile;

class ImageDataHandler
{
    /**
     * @param AlbumEntity $album
     * @param UploadedFile $uploadedFile
     * @return array
     */
    public function prepareData(AlbumEntity $album, UploadedFile $uploadedFile)
    {
        $image = [];
        $image['album_id'] = $album->getId();
        $image['title'] = $uploadedFile->getClientFilename();
        $image['mediaType'] = $uploadedFile->getClientMediaType();
        $image['path'] = $album->getPath(). DIRECTORY_SEPARATOR . $image['title'];
        $image['size'] = $uploadedFile->getSize();
        return $image;
    }
}
