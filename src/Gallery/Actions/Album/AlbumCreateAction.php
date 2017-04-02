<?php

namespace Gallery\Actions\Album;

use Aura\Web\Request;
use Gallery\Models\Album\AlbumService;
use Gallery\Responders\Album\AlbumCreateResponder;

/**
 * Class AlbumCreateAction
 * @package Gallery\Actions\Album
 */
class AlbumCreateAction
{
    /**
     * @var Request $request
     */
    protected $request;

    /**
     * @var AlbumService
     */
    protected $albumService;

    /**
     * @var AlbumCreateResponder $albumCreateResponder
     */
    protected $albumCreateResponder;

    /**
     * AlbumCreateAction constructor.
     * @param Request $request
     * @param AlbumService $albumService
     * @param AlbumCreateResponder $albumCreateResponder
     */
    public function __construct(
        Request $request,
        AlbumService $albumService,
        AlbumCreateResponder $albumCreateResponder
    ) {
        $this->request = $request;
        $this->albumService = $albumService;
        $this->albumCreateResponder = $albumCreateResponder;
    }

    /**
     * @return AlbumCreateResponder
     */
    public function __invoke()
    {
        $albumCreationData = $this->request->post->get('album');
        $albumCreationResult = $this->albumService->createAlbum($albumCreationData);
        $this->albumCreateResponder->setPayload($albumCreationResult);
        return $this->albumCreateResponder;
    }
}
