<?php

namespace Gallery\Actions\Gallery;

class GalleryEditAction extends AbstractGalleryAction
{
    public function __invoke($id = null)
    {
        $albumImagesFormCreation = $this->galleryService->getEditAlbumsImages($id);
        $this->galleryEditResponder->setPayload($albumImagesFormCreation);
        return $this->galleryEditResponder;
    }
}