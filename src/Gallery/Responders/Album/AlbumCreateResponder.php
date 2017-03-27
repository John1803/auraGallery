<?php

namespace Gallery\Responders\Album;

use Gallery\Models\Album\AlbumEntity;
use Gallery\Responders\Gallery\AbstractGalleryResponder;

class AlbumCreateResponder extends AbstractGalleryResponder
{
    protected $payload_method = ['FOA\DomainPayload\Created' => "created", ];

    protected function created()
    {
        /** @var AlbumEntity $album */
        $album = $this->payload->get('album');
        if (null === $album->getParent()) {
            $this->response->redirect->afterPost("/gallery/edit");
        } else {
            $this->response->redirect->afterPost("/edit/albums/images/{$album->getParent()}");
        }
    }
}
