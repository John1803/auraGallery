<?php

namespace Gallery\Models\Image;

use FOA\DomainPayload\PayloadFactory;
use Gallery\Input\ImageForm;
use Gallery\Models\Album\AlbumService;
use Zend\Diactoros\UploadedFile;

class ImageService
{
    /**
     * @var ImageMapper $imageMapper
     */
    protected $imageMapper;

    /**
     * @var ImageModel $imageModel
     */
    protected $imageModel;

    /**
     * @var ImageForm $imageForm
     */
    protected $imageForm;

    /**
     * @var ImageDataHandler $imageDataHandler
     */
    protected $imageDataHandler;

    /**
     * @var PayloadFactory $payloadFactory
     */
    protected $payloadFactory;

    /**
     * AlbumService constructor.
     * @param  AlbumService $albumService
     * @param ImageMapper $imageMapper
     * @param ImageModel $imageModel
     * @param ImageForm $imageForm
     * @param ImageDataHandler $imageDataHandler
     * @param PayloadFactory $payloadFactory
     */
    public function __construct(
        AlbumService $albumService,
        ImageMapper $imageMapper,
        ImageModel $imageModel,
        ImageForm $imageForm,
        ImageDataHandler $imageDataHandler,
        PayloadFactory $payloadFactory
    ) {
        $this->albumService = $albumService;
        $this->imageMapper = $imageMapper;
        $this->imageModel = $imageModel;
        $this->imageForm = $imageForm;
        $this->imageDataHandler = $imageDataHandler;
        $this->payloadFactory = $payloadFactory;
    }

    /**
     * @param array $data
     * @return \FOA\DomainPayload\NewEntity
     */
    public function newImage(array $data)
    {
        return $this->payloadFactory->newEntity([
            'image' => $this->imageMapper->newEntity($data),
            'imageForm' => $this->imageForm
        ]);
    }

    /**
     * @param array $uploadedImages
     * @param int $albumId
     * @return \FOA\DomainPayload\Created|\FOA\DomainPayload\Error
     */
    public function uploadImages(array $uploadedImages, int $albumId)
    {
        try {
            $albumPayload = $this->albumService->getAlbumById($albumId);
            $album = $albumPayload->get('album');
            /** @var UploadedFile $uploadedImage */
            foreach ($uploadedImages['images'] as $uploadedImage) {
                $preparedImageDataCreation = $this->imageDataHandler->prepareData($album, $uploadedImage);
                $this->imageModel->create($preparedImageDataCreation);
                $uploadedImage->moveTo($preparedImageDataCreation['path']);
            }

            return $this->payloadFactory->created([
                    'album' => $album,
                ]);

        } catch (\Exception $e) {
            return $this->payloadFactory->error([
                'exception' => $e,
                'uploadedImages' => $uploadedImages,
            ]);
        }
    }
}