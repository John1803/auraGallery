<?php

namespace Gallery\Actions\Gallery;

use Gallery\Models\Album\AlbumService;
use Gallery\Responders\GalleryResponder;

class GalleryRootAlbumsAction
{
    /**
     * @var AlbumService
     */
    protected $albumService;

    /**
     * @var GalleryResponder $galleryResponder
     */
    protected $galleryResponder;

    /**
     * GalleryRootAlbumsAction constructor.
     * @param AlbumService $albumService
     * @param GalleryResponder $galleryResponder
     */
    public function __construct(
        AlbumService $albumService,
        GalleryResponder $galleryResponder)
    {
        $this->albumService = $albumService;
        $this->galleryResponder = $galleryResponder;
    }

    public function __invoke()
    {
        $albums = $this->albumService->getRootAlbums();
        $this->galleryResponder->setPayload($albums);
        return $this->galleryResponder;
    }
}