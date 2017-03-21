<?php

namespace Gallery\Models\Album;

use FOA\DomainPayload\PayloadFactory;

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
     * @var PayloadFactory $payloadFactory
     */
    protected $payloadFactory;

    /**
     * AlbumService constructor.
     * @param AlbumMapper $albumMapper
     * @param AlbumModel $albumModel
     * @param PayloadFactory $payloadFactory
     */

    public function __construct(
        AlbumMapper $albumMapper,
        AlbumModel $albumModel,
        PayloadFactory $payloadFactory
    )
    {
        $this->albumMapper = $albumMapper;
        $this->albumModel = $albumModel;
        $this->payloadFactory = $payloadFactory;
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
}