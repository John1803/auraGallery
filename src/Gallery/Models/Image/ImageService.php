<?php

namespace Gallery\Models\Image;

use FOA\DomainPayload\PayloadFactory;
use Gallery\Models\Album\AlbumMapper;
use Gallery\Models\Album\AlbumModel;

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

    protected $albumMapper;
    protected $albumModel;

    /**
     * @var PayloadFactory $payloadFactory
     */
    protected $payloadFactory;

    /**
     * ImageService constructor.
     * @param AlbumMapper $albumMapper
     * @param AlbumModel $albumModel
     * @param ImageMapper $imageMapper
     * @param ImageModel $imageModel
     * @param PayloadFactory $payloadFactory
     */

    public function __construct(
        AlbumMapper $albumMapper,
        AlbumModel $albumModel,
        ImageMapper $imageMapper,
        ImageModel $imageModel,
        PayloadFactory $payloadFactory
    )
    {
        $this->imageMapper = $imageMapper;
        $this->imageModel = $imageModel;
        $this->albumMapper = $albumMapper;
        $this->albumModel = $albumModel;
        $this->payloadFactory = $payloadFactory;
    }

    public function getImagesAndAlbum($id)
    {
        try {
            $imagesRows = $this->imageModel->fetchImagesOfAlbum($id);
            $albumsRows = $this->albumModel->fetchDirectDescendantAlbums($id);
            if($imagesRows || $albumsRows) {
                $images = $this->imageMapper->newCollection($imagesRows);
                $albums = $this->albumMapper->newCollection($albumsRows);
                return $this->payloadFactory->found(["images" => $images,
                                                        "albums" => $albums,
                                                    ]
                );
            } else {
                return $this->payloadFactory->notFound([]);
            }
        } catch (\Exception $e) {
            return $this->payloadFactory->error(["exception" => $e, ]);
        }
    }
}