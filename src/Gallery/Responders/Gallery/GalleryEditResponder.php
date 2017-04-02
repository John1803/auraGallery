<?php

namespace Gallery\Responders\Gallery;

class GalleryEditResponder extends AbstractGalleryResponder
{
    protected $payload_method = ['FOA\DomainPayload\Found' => 'found', ];

    protected function found()
    {
        $this->renderView('galleryEdit', 'baseEdit');
    }
}