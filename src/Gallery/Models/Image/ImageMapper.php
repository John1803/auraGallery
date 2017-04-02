<?php

namespace Gallery\Models\Image;

use Gallery\Models\AbstractMapper;

/**
 * Class ImageMapper
 * @package Gallery\Models\Image
 */
class ImageMapper extends AbstractMapper
{
    /**
     * @param array $row Associative array of fetched image's row from DB
     * @return ImageEntity
     */
    public function newEntity($row)
    {
        return new ImageEntity($row);
    }
}
