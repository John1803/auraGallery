<?php

namespace Gallery\Responders\Image;

use Gallery\Models\Album\AlbumEntity;
use Gallery\Responders\Gallery\AbstractGalleryResponder;

/**
 * Class ImageUploadResponder
 * @package Gallery\Responders\Image
 */
class ImageUploadResponder extends AbstractGalleryResponder
{
    protected $payload_method = ['FOA\DomainPayload\Created' => 'created', ];

    protected function created()
    {
        /** @var AlbumEntity $album */
        $album = $this->payload->get('album');
        $this->response->redirect->afterPost('/edit/albums/images/' . $album->getId());
    }
}