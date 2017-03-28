<?php

namespace Gallery\Actions\Gallery;

/**
 * Class GalleryRootAlbumsAction
 * @package Gallery\Actions\Gallery
 */
class GalleryRootAlbumsAction extends AbstractGalleryAction
{
    public function __invoke()
    {
        $albums = $this->albumService->getRootAlbums();
        $this->galleryResponder->setPayload($albums);
        return $this->galleryResponder;
    }
}