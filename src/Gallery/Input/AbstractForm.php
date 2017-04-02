<?php

namespace Gallery\Input;

use Aura\Input\BuilderInterface;
use Aura\Input\FilterInterface;
use Aura\Input\Form;
use Gallery\Models\Album\AlbumEntity;
use Gallery\Models\Album\AlbumService;

class AbstractForm extends Form
{
    /**
     * @var AlbumService $albumService
     */
    protected $albumService;
    /**
     * AbstractImageForm constructor.
     * @param BuilderInterface $builder
     * @param FilterInterface $filter
     * @param null $options
     * @param AlbumService $albumService
     */
    public function __construct(
        BuilderInterface $builder,
        FilterInterface $filter,
        $options = null,
        AlbumService $albumService
    ) {
        $this->albumService = $albumService;
        parent::__construct($builder, $filter, $options);
    }

    /**
     * This method prepare album's data for drop-down list (select-option) to ImageForm
     *
     * @return array
     */
    public function prepareSelectOptionsData()
    {
        $albumOptions = [];
        $albumsTree = $this->albumService->getAlbumsTree();
        /** @var array $albums */
        $albums = $albumsTree->get('albums');

        /** @var AlbumEntity $album */
        foreach ($albums as $album) {
            $albumOptions[$album->getId()] = $album->getTitle();
        }

        return $albumOptions;
    }
}