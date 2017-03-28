<?php

namespace Gallery\Models\Album;

use Gallery\Models\AbstractModel;

/**
 * Class AlbumModel
 * @package Gallery\Models\Album
 */

class AlbumModel extends AbstractModel
{
    /**
     * @return array
     */
    public function fetchAllRootAlbums()
    {
        $rootAlbums = $this->pdo->fetchAll(
            'SELECT a.id, a.parent, a.title, a.path, a.lft, a.rgt, a.lvl
            FROM albums a
            WHERE a.lvl = :lvl;',
            ['lvl' => 0, ]
        );

        return $rootAlbums;
    }

    /**
     * @param int $id
     * @return array
     */
    public function fetchDirectDescendantAlbums($id)
    {
        $albums = $this->pdo->fetchAll(
            'SELECT a.id, a.parent, a.title, a.path, a.lft, a.rgt, a.lvl
            FROM albums AS a
            JOIN albums AS ancestor
            ON ancestor.id = :id
            AND a.lvl > ancestor.lvl
            AND a.lvl < ancestor.lvl + 2
            AND a.lft
            BETWEEN ancestor.lft AND ancestor.rgt;',
            ['id' => $id, ]
        );

        return $albums;
    }

    /**
     * @param int $id
     * @return array|false
     */
    public function fetchAlbumById($id)
    {
        $albumRow = $this->pdo->fetchOne(
            'SELECT a.id, a.parent, a.title, a.path, a.lft, a.rgt, a.lvl
            FROM albums As a
            WHERE id = :id;',
            ['id' => $id, ]
        );

        return $albumRow;
    }

    /**
     * @return array
     */
    public function fetchAlbumsTree()
    {
        $albumsRow = $this->pdo->fetchAll(
            'SELECT a.id, a.title, a.parent, a.path, a.lft, a.rgt, a.lvl 
            FROM albums AS a
            JOIN albums AS ancestor
            ON a.lft BETWEEN ancestor.lft AND ancestor.rgt
            GROUP BY a.title
            ORDER BY a.lft;'
        );

        return $albumsRow;
    }

    /**
     * @param array $data
     * @return array|false
     */
    public function create(array $data)
    {
        $sql = 'UPDATE albums SET rgt = rgt + 2 WHERE rgt >= :leftPosition;
                UPDATE albums SET lft = lft + 2 WHERE lft >= :leftPosition;
                INSERT INTO albums(parent, title, path, lft, rgt, lvl) 
                VALUES(:parent, :title, :path, :leftPosition, :leftPosition + 1, :levelPosition);';
        $this->pdo->beginTransaction();
        $this->pdo->perform($sql, ['parent' => $data['parent'],
                                    'title' => $data['title'],
                                    'path' => $data['path'],
                                    'leftPosition' => $data['lft'],
                                    'levelPosition' => $data['lvl'],]
        );
        $id = $this->pdo->lastInsertId();
        $this->pdo->commit();

        return $this->fetchAlbumById($id);
    }

    /**
     * @return array
     */
    public function fetchAlbumWithMaxRightProperty()
    {
        $maxRight = (int)$this->fetchMaxRightValue();
        $albumRow = $this->pdo->fetchOne('
            SELECT a.id, a.title, a.parent, a.path, a.lft, a.rgt, a.lvl
            FROM albums AS a 
            WHERE a.rgt = :maxValue AND a.lvl = 0;',
            ['maxValue' => $maxRight, ]
        );

        return $albumRow;
    }

    /**
     * @return int
     */
    private function fetchMaxRightValue()
    {
        $result = $this->pdo->fetchOne('SELECT @maxRight := MAX(rgt) AS maxRight FROM albums;');
        return $result['maxRight'];
    }
}
