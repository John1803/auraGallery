<?php

namespace Gallery\Actions\Gallery;

use Gallery\Models\Album\AlbumService;
use Gallery\Models\Gallery\GalleryService;
use Gallery\Responders\Gallery\GalleryEditResponder;
use Gallery\Responders\Gallery\GalleryImagesAlbumsResponder;
use Gallery\Responders\Gallery\GalleryResponder;

class AbstractGalleryAction
{
    /**
     * @var GalleryService $galleryService
     */
    protected $galleryService;

    /**
     * @var AlbumService $albumService
     */
    protected $albumService;

    /**
     * @var GalleryResponder $galleryResponder
     */
    protected $galleryResponder;

    /**
     * @var GalleryEditResponder $galleryEditResponder
     */
    protected $galleryEditResponder;

    /**
     * @var GalleryImagesAlbumsResponder $galleryImagesAlbumsResponder
     */
    protected $galleryImagesAlbumsResponder;

    /**
     * AbstractGalleryAction constructor.
     * @param GalleryService $galleryService
     * @param AlbumService $albumService
     * @param GalleryResponder $galleryResponder
     * @param GalleryEditResponder $galleryEditResponder
     * @param GalleryImagesAlbumsResponder $galleryImagesAlbumsResponder
     */
    public function __construct(
        GalleryService $galleryService,
        AlbumService $albumService,
        GalleryResponder $galleryResponder,
        GalleryEditResponder $galleryEditResponder,
        GalleryImagesAlbumsResponder $galleryImagesAlbumsResponder
    )
    {
        $this->galleryService =$galleryService;
        $this->albumService = $albumService;
        $this->galleryResponder = $galleryResponder;
        $this->galleryEditResponder = $galleryEditResponder;
        $this->galleryImagesAlbumsResponder = $galleryImagesAlbumsResponder;
    }
}