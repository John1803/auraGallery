<?php

namespace Gallery\Actions\Album;

use Gallery\Models\Album\AlbumService;
use Gallery\Responders\Album\AlbumNewResponder;

class AlbumNewAction
{
    /**
     * @var AlbumService $albumService
     */
    protected $albumService;

    /**
     * @var AlbumNewResponder $albumNewResponder
     */
    protected $albumNewResponder;

    /**
     * AlbumNewAction constructor.
     * @param AlbumService $albumService
     * @param AlbumNewResponder $albumNewResponder
     */
    public function __construct(
         AlbumService $albumService,
         AlbumNewResponder $albumNewResponder
    )
    {
        $this->albumService = $albumService;
        $this->albumNewResponder = $albumNewResponder;
    }

    public function __invoke()
    {
        $this->albumNewResponder->setPayload($this->albumService->newAlbum([]));
        return $this->albumNewResponder;
    }
}