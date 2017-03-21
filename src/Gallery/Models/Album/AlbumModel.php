<?php

namespace Gallery\Models\Album;

use Gallery\Models\AbstractModel;

class AlbumModel extends AbstractModel
{
    public function fetchAllRootAlbums()
    {
        $rootAlbums = $this->pdo->fetchAll("SELECT a.*
                                            FROM albums a
                                            WHERE a.lvl = :lvl;",
                                            ["lvl" => 0, ]
        );

        return $rootAlbums;
    }

    public function fetchDirectDescendantAlbums($id)
    {
        $albums = $this->pdo->fetchAll("SELECT descendant.id, 
                                                descendant.title, 
                                                descendant.path, 
                                                descendant.lft, 
                                                descendant.rgt, 
                                                descendant.lvl
                                        FROM albums AS descendant
                                        JOIN albums AS ancestor
                                        ON ancestor.id = :id
                                        AND descendant.lvl > ancestor.lvl
                                        AND descendant.lvl < ancestor.lvl + 2
                                        AND descendant.lft
                                        BETWEEN ancestor.lft AND ancestor.rgt;",
                                        ["id" => $id, ]
        );

        return $albums;
    }
}