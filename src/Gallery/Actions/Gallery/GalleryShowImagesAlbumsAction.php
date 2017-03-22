<?php

namespace Gallery\Actions\Gallery;

class GalleryShowImagesAlbumsAction extends AbstractGalleryAction
{
    public function __invoke($id)
    {
        $imagesAlbums = $this->galleryService->getImagesAndAlbum($id);
        $this->galleryImagesAlbumsResponder->setPayload($imagesAlbums);
        return $this->galleryImagesAlbumsResponder;
    }
}