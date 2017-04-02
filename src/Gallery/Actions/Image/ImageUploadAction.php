<?php

namespace Gallery\Actions\Image;

use Gallery\Models\Image\ImageService;
use Gallery\Responders\Image\ImageUploadResponder;
use Zend\Diactoros\ServerRequestFactory;

/**
 * Class ImageUploadAction
 * @package Gallery\Actions\Image
 */
class ImageUploadAction
{
    /**
     * @var ImageService
     */
    protected $imageService;

    /**
     * @var ImageUploadResponder $albumCreateResponder
     */
    protected $albumCreateResponder;

    /**
     * AlbumCreateAction constructor.
     * @param ImageService $imageService
     * @param ImageUploadResponder $imageUploadResponder
     */
    public function __construct(
        ImageService $imageService,
        ImageUploadResponder $imageUploadResponder
    ) {
        $this->imageService = $imageService;
        $this->imageUploadResponder = $imageUploadResponder;
    }

    /**
     * @return ImageUploadResponder
     */
    public function __invoke()
    {
        $request = ServerRequestFactory::fromGlobals();
        $uploadedImages = $request->getUploadedFiles();
        $imagesAlbum = $request->getParsedBody()['imagesAlbum'];
        $uploadedImagesResult = $this->imageService->uploadImages($uploadedImages, $imagesAlbum);
        $this->imageUploadResponder->setPayload($uploadedImagesResult);
        return $this->imageUploadResponder;
    }
}