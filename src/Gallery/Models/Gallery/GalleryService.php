<?php

namespace Gallery\Models\Gallery;

use FOA\DomainPayload\PayloadFactory;
use Gallery\Models\Album\AlbumMapper;
use Gallery\Models\Album\AlbumModel;
use Gallery\Models\Image\ImageMapper;
use Gallery\Models\Image\ImageModel;
use Gallery\Input\AlbumForm;

class GalleryService
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
     * @var AlbumMapper $albumMapper
     */
    protected $albumMapper;

    /**
     * @var AlbumModel $albumModel
     */
    protected $albumModel;

    /**
     * @var AlbumForm $albumForm
     */
    protected $albumForm;

    /**
     * @var PayloadFactory $payloadFactory
     */
    protected $payloadFactory;

    /**
     * GalleryService constructor.
     * @param AlbumMapper $albumMapper
     * @param AlbumModel $albumModel
     * @param ImageMapper $imageMapper
     * @param ImageModel $imageModel
     * @param AlbumForm $albumForm
     * @param PayloadFactory $payloadFactory
     */

    public function __construct(
        AlbumMapper $albumMapper,
        AlbumModel $albumModel,
        ImageMapper $imageMapper,
        ImageModel $imageModel,
        AlbumForm $albumForm,
        PayloadFactory $payloadFactory
    )
    {
        $this->imageMapper = $imageMapper;
        $this->imageModel = $imageModel;
        $this->albumMapper = $albumMapper;
        $this->albumModel = $albumModel;
        $this->albumForm = $albumForm;
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
                return $this->payloadFactory->found(['images' => $images,
                                                    'albums' => $albums, ]
                );

            } else {
                $albums = $this->albumMapper->newEntity([]);
                return $this->payloadFactory->notFound(['albums' => $albums, ]);
            }
        } catch (\Exception $e) {
            return $this->payloadFactory->error(['exception' => $e, ]);
        }
    }

    public function getEditAlbumsImages($id = null)
    {
        try {
            if (null === $id) {
                $album = $this->albumMapper->newEntity(['id' => 0]);
                $albumsRows = $this->albumModel->fetchAllRootAlbums();
                $albums = $this->albumMapper->newCollection($albumsRows);
                $images = $this->imageMapper->newEntity([]);
                return $this->payloadFactory->found(['album' => $album,
                        'albums' => $albums,
                        'albumForm' => $this->albumForm,
                        'images' => $images, ]
                );
            } else {
                $albumRow = $this->albumModel->fetchAlbumById($id);
                $imagesRows = $this->imageModel->fetchImagesOfAlbum($id);
                $albumsRows = $this->albumModel->fetchDirectDescendantAlbums($id);
                $album = $this->albumMapper->newEntity($albumRow);
                $images = $this->imageMapper->newCollection($imagesRows);
                $albums = $this->albumMapper->newCollection($albumsRows);
                return $this->payloadFactory->found(['album' => $album,
                                                        'albums' => $albums,
                                                        'albumForm' => $this->albumForm,
                                                        'images' => $images, ]
                );
            }
        } catch (\Exception $e) {
            return $this->payloadFactory->error(['exception' => $e, ]);
        }
    }
}
