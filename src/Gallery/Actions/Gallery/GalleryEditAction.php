<?php

namespace Gallery\Actions\Gallery;

/**
 * Class GalleryEditAction
 * @package Gallery\Actions\Gallery
 */
class GalleryEditAction extends AbstractGalleryAction
{
    /**
     * @param int|null $id
     * @return \Gallery\Responders\Gallery\GalleryEditResponder
     */
    public function __invoke($id = null)
    {
        $albumImagesFormCreation = $this->galleryService->getEditAlbumsImages($id);
        $this->galleryEditResponder->setPayload($albumImagesFormCreation);
        return $this->galleryEditResponder;
    }
}