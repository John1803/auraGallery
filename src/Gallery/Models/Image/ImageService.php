<?php

namespace Gallery\Models\Image;

use FOA\DomainPayload\PayloadFactory;
use Gallery\Input\ImageForm;

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
     * @var PayloadFactory $payloadFactory
     */
    protected $payloadFactory;

    /**
     * AlbumService constructor.
     * @param ImageMapper $imageMapper
     * @param ImageModel $imageModel
     * @param ImageForm $imageForm
     * @param PayloadFactory $payloadFactory
     */

    public function __construct(
        ImageMapper $imageMapper,
        ImageModel $imageModel,
        ImageForm $imageForm,
        PayloadFactory $payloadFactory
    )
    {
        $this->imageMapper = $imageMapper;
        $this->imageModel = $imageModel;
        $this->imageForm = $imageForm;
        $this->payloadFactory = $payloadFactory;
    }

    public function newImage(array $data)
    {
        return $this->payloadFactory->newEntity([
            'image' => $this->imageMapper->newEntity($data),
            'imageForm' => $this->imageForm
        ]);
    }
}