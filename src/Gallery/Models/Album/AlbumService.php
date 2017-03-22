<?php

namespace Gallery\Models\Album;

use FOA\DomainPayload\PayloadFactory;
use Gallery\Input\AlbumForm;

class AlbumService
{
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
     * AlbumService constructor.
     * @param AlbumMapper $albumMapper
     * @param AlbumModel $albumModel
     * @param AlbumForm $albumForm
     * @param PayloadFactory $payloadFactory
     */

    public function __construct(
        AlbumMapper $albumMapper,
        AlbumModel $albumModel,
        PayloadFactory $payloadFactory,
        AlbumForm $albumForm
    )
    {
        $this->albumMapper = $albumMapper;
        $this->albumModel = $albumModel;
        $this->payloadFactory = $payloadFactory;
        $this->albumForm = $albumForm;

    }

    public function getRootAlbums()
    {
        try {
            $rows = $this->albumModel->fetchAllRootAlbums();
            if($rows) {
                $albums = $this->albumMapper->newCollection($rows);
                return $this->payloadFactory->found(["albums" => $albums, ]);
            } else {
                return $this->payloadFactory->notFound([]);
            }
        } catch (\Exception $e) {
            return $this->payloadFactory->error(["exception" => $e, ]);
        }
    }

    public function getDirectDescendantAlbums($id)
    {
        try {
            $rows = $this->albumModel->fetchDirectDescendantAlbums($id);
            if($rows) {
                $albums = $this->albumMapper->newCollection($rows);
                return $this->payloadFactory->found(["albums" => $albums, ]);
            } else {
                return $this->payloadFactory->notFound([]);
            }
        } catch (\Exception $e) {
            return $this->payloadFactory->error(["exception" => $e, ]);
        }
    }

    public function newAlbum(array $data)
    {
        return $this->payloadFactory->newEntity([
            'album' => $this->albumMapper->newEntity($data),
            'albumForm' => $this->albumForm
        ]);
    }
}