<?php

namespace Gallery\Input;

use Aura\Input\Form;

class AlbumForm extends Form
{
    public function init()
    {
        $this->setField("album", "text")
                ->setAttribs([
                    'id' => "album",
                    'name' => "album[title]",
                ]
        );
    }
}
