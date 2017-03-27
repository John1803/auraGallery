<?php

namespace Gallery\Models\Album;

class AlbumDataHandler
{
    /**
     * This property contains path to the working directory
     *
     * @var string
     */
    protected $path;

    /**
     * @var AlbumModel $albumModel
     */
    protected $albumModel;

    /**
     * AlbumDataHandler constructor.
     * @param AlbumModel $albumModel
     * @param string $path
     */
    public function __construct(
        AlbumModel $albumModel,
        string $path
    )
    {
        $this->albumModel = $albumModel;
        $this->path = $path;
    }

    /**
     * This method receives data from album form creation.
     * It determines which kind of album to create. It have to be root or descendant album
     *
     * @param array $albumData
     * @return array
     */
    public function prepareData(array $albumData)
    {
        if (true === (bool)$albumData['parent'] &&
            0 <= (int)$this->albumModel->fetchAlbumById($albumData['parent'])['lvl']) {
            $albumAncestor = $this->albumModel->fetchAlbumById($albumData['parent']);
            $albumHandledData = $this->prepareDescendantData($albumAncestor, $albumData);
            $albumData = $this->mergeReceivedHandledData($albumData, $albumHandledData);
        }  else if (($albumLastSibling = $this->albumModel->fetchAlbumWithMaxRightProperty()) ||
                    false === $albumLastSibling) {
                    if (false === $albumLastSibling) {
                        $albumLastSibling = ['parent' => null, 'lft' => 0, 'rgt' => 0, 'lvl' => 0];
                    }
            $albumHandledData = $this->prepareSiblingData($albumLastSibling, $albumData);
            $albumData = $this->mergeReceivedHandledData($albumData, $albumHandledData);
        }
        return $albumData;
    }

    /**
     * @param array $albumAncestor
     * @param array $albumNewDescendant Contains information from album creation form
     * @return array
     */
    public function prepareDescendantData(array $albumAncestor, array $albumNewDescendant)
    {
        $albumData['lft'] = (int)$albumAncestor['rgt'];
        $albumData['rgt'] = (int)$albumAncestor['rgt'] + 1;
        $albumData['lvl'] = (int)$albumAncestor['lvl'] + 1;
        $albumData['path'] = $albumAncestor['path'] . DIRECTORY_SEPARATOR . $albumNewDescendant['title'];

        return $albumData;
    }

    /**
     * @param array $albumLastSibling
     * @param array $albumNewSibling Contains information from album creation form
     * @return array
     */
    public function prepareSiblingData(array $albumLastSibling, array $albumNewSibling)
    {

        $albumData['parent'] = $albumLastSibling['parent'] ?
                                (int)$albumLastSibling['parent'] :
                                null;
        $albumData['path'] = $this->path . $albumNewSibling['title'];
        $albumData['lft'] = (int)$albumLastSibling['rgt'] + 1;
        $albumData['rgt'] = (int)$albumLastSibling['rgt'] + 2;
        $albumData['lvl'] = (int)$albumLastSibling['lvl'];


        return $albumData;
    }

    /**
     * @param array $albumFormData
     * @param array $albumHandledData
     * @return array
     */
    public function mergeReceivedHandledData(array $albumFormData, array $albumHandledData)
    {
        return array_merge($albumFormData, $albumHandledData);
    }
}