<?php

namespace Gallery\Actions\Gallery;

use Gallery\Models\Album\AlbumService;
use Gallery\Models\Image\ImageService;
use Gallery\Responders\GalleryImagesAlbumsResponder;

class GalleryShowImagesAlbumsAction
{
    /**
     * @var AlbumService
     */
    protected $albumService;

    /**
     * @var ImageService $imageService
     */
    protected $imageService;

    /**
     * @var GalleryImagesAlbumsResponder $galleryImagesAlbumsResponder
     */
    protected $galleryImagesAlbumsResponder;

    /**
     * GalleryRootAlbumsAction constructor.
     * @param AlbumService $albumService
     * @param ImageService $imageService
     * @param GalleryImagesAlbumsResponder $galleryImagesAlbumsResponder
     */
    public function __construct(
        AlbumService $albumService,
        ImageService $imageService,
        GalleryImagesAlbumsResponder $galleryImagesAlbumsResponder)
    {
        $this->albumService = $albumService;
        $this->imageService = $imageService;
        $this->galleryImagesAlbumsResponder = $galleryImagesAlbumsResponder;
    }

    public function __invoke($id)
    {
        $imagesAlbums = $this->imageService->getImagesAndAlbum($id);
        $this->galleryImagesAlbumsResponder->setPayload($imagesAlbums);

        return $this->galleryImagesAlbumsResponder;
    }
}