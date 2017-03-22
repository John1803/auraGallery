<?php

namespace Gallery\Responders\Gallery;

class GalleryResponder extends AbstractGalleryResponder
{
    protected $payload_method = array(
        "FOA\DomainPayload\Found" => "found",
        "FOA\DomainPayload\NotFound" => "notFound",
    );

    protected function found()
    {
        $this->renderView("rootAlbums", "base");
    }
}