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
                'name' => 'image[]',
            ]);

        $this->setField('imageTwo', 'file')
            ->setAttribs([
                'id' => 'image-two',
                'name' => 'image[]',
            ]);

        $this->setField('albums', 'select')
            ->setAttribs(['id' => 'albums',])
            ->setOptions($albums);
    }
}
