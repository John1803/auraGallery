<?php

namespace Gallery\Actions\Image;

use Gallery\Models\Image\ImageService;
use Gallery\Responders\Image\ImageNewResponder;

/**
 * Class ImageNewAction
 * @package Gallery\Actions\Image
 */
class ImageNewAction
{
    /**
     * @var ImageService $imageService
     */
    protected $imageService;

    /**
     * @var ImageNewResponder $imageNewResponder
     */
    protected $imageNewResponder;

    /**
     * AlbumNewAction constructor.
     * @param ImageService $imageService
     * @param ImageNewResponder $imageNewResponder
     */
    public function __construct(
        ImageService $imageService,
        ImageNewResponder $imageNewResponder
    ) {
        $this->imageService = $imageService;
        $this->imageNewResponder = $imageNewResponder;
    }

    /**
     * @return ImageNewResponder
     */
    public function __invoke()
    {
        $this->imageNewResponder->setPayload($this->imageService->newImage([]));
        return $this->imageNewResponder;
    }
}