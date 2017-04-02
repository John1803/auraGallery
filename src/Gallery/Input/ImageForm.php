<?php

namespace Gallery\Input;

class ImageForm extends AbstractForm
{
    public function init()
    {
        $albums = $this->prepareSelectOptionsData();

        $this->setField('imageOne', 'file')
            ->setAttribs([
                'id' => 'image-one',
                'name' => 'images[]',
            ]);

        $this->setField('imageTwo', 'file')
            ->setAttribs([
                'id' => 'image-two',
                'name' => 'images[]',
            ]);

        $this->setField('imagesAlbum', 'select')
            ->setAttribs([
                'id' => 'albums',
                'name' => 'imagesAlbum'
            ])
            ->setOptions($albums);
    }
}
