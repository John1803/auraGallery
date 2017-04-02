<?php

namespace Gallery\Actions;

use Aura\Web\Response;

class HelloBaby
{

    public function __construct(Response $response)
    {
        $this->response = $response;
    }

    public function __invoke()
    {
        $content = "Hello baby";
        $this->response->content->set($content);
    }
}