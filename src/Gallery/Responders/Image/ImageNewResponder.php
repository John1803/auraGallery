<?php

namespace Gallery\Responders\Image;

use Gallery\Responders\Gallery\AbstractGalleryResponder;

class ImageNewResponder extends AbstractGalleryResponder
{
    protected $payload_method = array(
        'FOA\DomainPayload\NewEntity' => 'display',
    );

    protected function display()
    {
        $this->renderView('imageNew', 'base');
    }
}