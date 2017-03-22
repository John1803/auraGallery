<?php

namespace Gallery\Actions\Gallery;

class GalleryEditAction extends AbstractGalleryAction
{
    public function __invoke()
    {
        $albums = $this->albumService->getRootAlbums();
        $this->galleryEditResponder->setPayload($albums);
        return $this->galleryEditResponder;
    }
}