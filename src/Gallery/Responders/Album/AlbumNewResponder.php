<?php

namespace Gallery\Responders\Album;

use Gallery\Responders\Gallery\AbstractGalleryResponder;

class AlbumNewResponder extends AbstractGalleryResponder
{
    protected $payload_method = array(
        'FOA\DomainPayload\NewEntity' => 'display',
    );

    protected function display()
    {
        $this->renderView("newAlbum", "base");
    }
}