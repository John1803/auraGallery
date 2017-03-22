<?php

namespace Gallery\Responders\Gallery;

class GalleryEditResponder extends AbstractGalleryResponder
{
    protected $payload_method = array(
        "FOA\DomainPayload\Found" => "found",
        "FOA\DomainPayload\NotFound" => "notFound",
    );

    protected function found()
    {
        $this->renderView("galleryEdit", "baseEdit");
    }
}