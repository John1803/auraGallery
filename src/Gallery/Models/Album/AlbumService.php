<?php

namespace Gallery\Models\Album;

use Filesystem\FilesystemInterface;
use FOA\DomainPayload\PayloadFactory;
use Gallery\Input\AlbumForm;

/**
 * Class AlbumService
 * @package Gallery\Models\Album
 */
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
     * @var AlbumDataHandler $albumDataHandler
     */
    protected $albumDataHandler;

    /**
     * @var FilesystemInterface $filesystem
     */

    protected $filesystem;
    /**
     * @var PayloadFactory $payloadFactory
     */
    protected $payloadFactory;

    /**
     * AlbumService constructor.
     * @param AlbumMapper $albumMapper
     * @param AlbumModel $albumModel
     * @param AlbumDataHandler $albumDataHandler
     * @param FilesystemInterface $filesystem
     * @param AlbumForm $albumForm
     * @param PayloadFactory $payloadFactory
     */

    public function __construct(
        AlbumMapper $albumMapper,
        AlbumModel $albumModel,
        AlbumDataHandler $albumDataHandler,
        FilesystemInterface $filesystem,
        PayloadFactory $payloadFactory,
        AlbumForm $albumForm
    ) {
        $this->albumMapper = $albumMapper;
        $this->albumModel = $albumModel;
        $this->albumDataHandler = $albumDataHandler;
        $this->filesystem = $filesystem;
        $this->payloadFactory = $payloadFactory;
        $this->albumForm = $albumForm;

    }

    /**
     * @param int $id
     * @return \FOA\DomainPayload\Error|\FOA\DomainPayload\Found|\FOA\DomainPayload\NotFound
     */
    public function getAlbumById(int $id)
    {
        try {
            $albumRow = $this->albumModel->fetchAlbumById($id);
            if($albumRow) {
                $album = $this->albumMapper->newEntity($albumRow);
                return $this->payloadFactory->found(['album' => $album, ]);
            }
            return $this->payloadFactory->notFound([]);
        } catch (\Exception $e) {
            return $this->payloadFactory->error(['exception' => $e, ]);
        }
    }
    /**
     * @return \FOA\DomainPayload\Error|\FOA\DomainPayload\Found|\FOA\DomainPayload\NotFound
     */
    public function getRootAlbums()
    {
        try {
            $rows = $this->albumModel->fetchAllRootAlbums();
            if($rows) {
                $albums = $this->albumMapper->newCollection($rows);
                return $this->payloadFactory->found(["albums" => $albums, ]);
            }
            $albums = [];
            return $this->payloadFactory->notFound(['albums' => $albums, ]);
        } catch (\Exception $e) {
            return $this->payloadFactory->error(["exception" => $e, ]);
        }
    }

    /**
     * @param int $id
     * @return \FOA\DomainPayload\Error|\FOA\DomainPayload\Found|\FOA\DomainPayload\NotFound
     */
    public function getDirectDescendantAlbums($id)
    {
        try {
            $rows = $this->albumModel->fetchDirectDescendantAlbums($id);
            if($rows) {
                $albums = $this->albumMapper->newCollection($rows);
                return $this->payloadFactory->found(['albums' => $albums, ]);
            }

            return $this->payloadFactory->notFound([]);

        } catch (\Exception $e) {
            return $this->payloadFactory->error(['exception' => $e, ]);
        }
    }

    /**
     * @return \FOA\DomainPayload\Error| \FOA\DomainPayload\Found| \FOA\DomainPayload\NotFound
     */
    public function getAlbumsTree()
    {
        try {
            $rows = $this->albumModel->fetchAlbumsTree();
            if($rows) {
                $albums = $this->albumMapper->newCollection($rows);
                return $this->payloadFactory->found(['albums' => $albums, ]);
            }
            return $this->payloadFactory->notFound([]);
        } catch (\Exception $e) {
            return $this->payloadFactory->error(['exception' => $e, ]);
        }
    }
    /**
     * @param array $data
     * @return \FOA\DomainPayload\NewEntity
     */
    public function newAlbum(array $data)
    {
        return $this->payloadFactory->newEntity([
            'album' => $this->albumMapper->newEntity($data),
            'albumForm' => $this->albumForm
        ]);
    }

    /**
     * @param array $data
     * @return \FOA\DomainPayload\Created|\FOA\DomainPayload\Error
     */
    public function createAlbum(array $data)
    {
        try {
            $preparedData = $this->albumDataHandler->prepareData($data);
            $albumRow = $this->albumModel->create($preparedData);
            if ($albumRow) {
                $album = $this->albumMapper->newEntity($albumRow);
                $this->filesystem->mkdir($album->getPath());
                return $this->payloadFactory->created(['album' => $album, ]);
            }
        } catch (\Exception $e) {
            return $this->payloadFactory->error([
                'exception' => $e,
                'data' => $data,
            ]);
        }
    }
}
