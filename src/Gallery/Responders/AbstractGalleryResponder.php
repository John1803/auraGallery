<?php

namespace Gallery\Responders;

use FOA\Responder_Bundle\AbstractResponder;

abstract class AbstractGalleryResponder extends AbstractResponder
{
    protected function init()
    {
        parent::init();

        $templates = [
            "rootAlbums",
        ];

        $layouts = [
            "base",
            "sidebar",
        ];
        /**
         * @var \Aura\View\View $auraEngine
         */
        $auraEngine = $this->getRenderer()->getEngine();

        $view_registry = $auraEngine->getViewRegistry();
        foreach ($templates as $template) {
            $view_registry->set(
                $template,
                __DIR__ . "../../views/templates/{$template}.phtml"
            );
        }

        $layout_registry = $auraEngine->getLayoutRegistry();
        foreach ($layouts as $layout) {
            $layout_registry->set(
                $layout,
                __DIR__ . "../../views/layouts/{$layout}.phtml"
            );
        }
    }
}