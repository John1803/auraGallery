<?php

namespace Gallery\Input;

use Aura\Input\BuilderInterface;
use Aura\Input\FilterInterface;
use Aura\Input\Form;
use Gallery\Models\Album\AlbumModel;

class AbstractAlbumForm extends Form
{
    /**
     * @var AlbumModel $albumModel
     */
    protected $albumModel;
    /**
     * AbstractImageForm constructor.
     * @param BuilderInterface $builder
     * @param FilterInterface $filter
     * @param null $options
     * @param AlbumModel $albumModel
     */
    public function __construct(
        BuilderInterface $builder,
        FilterInterface $filter,
        $options = null,
        AlbumModel $albumModel
    ) {
        $this->albumModel = $albumModel;
        parent::__construct($builder, $filter, $options);
    }
}

