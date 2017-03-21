<?php

namespace Gallery\Actions\Gallery;

use Gallery\Models\Gallery\GalleryService;
use Gallery\Responders\GalleryImagesAlbumsResponder;

class GalleryShowImagesAlbumsAction
{
    /**
     * @var GalleryService $galleryService
     */
    protected $galleryService;

    /**
     * GalleryRootAlbumsAction constructor.
     * @param GalleryService $galleryService
     * @param GalleryImagesAlbumsResponder $galleryImagesAlbumsResponder
     */
    public function __construct(
        GalleryService $galleryService,
        GalleryImagesAlbumsResponder $galleryImagesAlbumsResponder)
    {
        $this->galleryService = $galleryService;
        $this->galleryImagesAlbumsResponder = $galleryImagesAlbumsResponder;
    }

    public function __invoke($id)
    {
        $imagesAlbums = $this->galleryService->getImagesAndAlbum($id);
        $this->galleryImagesAlbumsResponder->setPayload($imagesAlbums);
        return $this->galleryImagesAlbumsResponder;
    }
}