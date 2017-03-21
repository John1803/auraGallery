<?php

namespace Html\Helper;

use Aura\Html\Helper\AbstractHelper;
use Aura\Router\Router as AuraRouter;

class Router extends AbstractHelper
{
    /**
     * @var AuraRouter $router
     */
    protected $router;

    /**
     * Router constructor.
     * @param AuraRouter $router
     */
    public function __construct(AuraRouter $router)
    {
        $this->router = $router;
    }

    public function __invoke()
    {
        return $this->router;
    }
}