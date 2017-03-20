<?php

namespace Gallery\Models\Album;

use Aura\Sql\ExtendedPdo;

class AlbumModel
{
    public function __construct(ExtendedPdo $pdo)
    {
        $this->pdo = $pdo;
    }

    public function fetchAllRootAlbums()
    {
        $rootAlbums = $this->pdo->fetchAll("SELECT a.*
                                            FROM albums a
                                            WHERE a.lvl = :lvl;",
                                            ["lvl" => 0, ]
        );

        return $rootAlbums;
    }
}