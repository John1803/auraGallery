<?php

namespace Gallery\Models\Album;

class AlbumDataHandler
{
    /**
     * @var AlbumModel $albumModel
     */
    protected $albumModel;

    public function __construct(AlbumModel $albumModel)
    {
        $this->albumModel = $albumModel;
    }

    public function prepareData(array $albumData)
    {
        if (true === (bool)$albumData['parent'] &&
            0 <= (int)$this->albumModel->fetchAlbumById($albumData['parent'])['lvl']) {
            $albumAncestor = $this->albumModel->fetchAlbumById($albumData['parent']);
            $albumHandledData = $this->prepareDescendantData($albumAncestor, $albumData);
            $albumData = $this->mergeReceivedHandledData($albumData, $albumHandledData);
        }  else if ($albumLastSibling = $this->albumModel->fetchAlbumWithMaxRightProperty()) {
            $albumHandledData = $this->prepareSiblingData($albumLastSibling);
            $albumData = $this->mergeReceivedHandledData($albumData, $albumHandledData);
        }
        return $albumData;
    }

    public function prepareDescendantData($albumAncestor, $data)
    {
        $albumData['lft'] = (int)$albumAncestor['rgt'];
        $albumData['rgt'] = (int)$albumAncestor['rgt'] + 1;
        $albumData['lvl'] = (int)$albumAncestor['lvl'] + 1;
        $albumData['path'] = $albumAncestor['path'] . DIRECTORY_SEPARATOR . $data['title'];

        return $albumData;
    }

    public function prepareSiblingData($albumRow)
    {
        $albumData['lft'] = (int)$albumRow['rgt'] + 1;
        $albumData['rgt'] = (int)$albumRow['rgt'] + 2;
        $albumData['lvl'] = (int)$albumRow['lvl'];
        $albumData['parent'] = $albumRow['parent'] ? (int)$albumRow['parent'] : null;

        return $albumData;
    }

    public function mergeReceivedHandledData(array $albumFormData, array $albumHandledData)
    {
        return array_merge($albumFormData, $albumHandledData);
    }
}