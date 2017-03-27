<?php
namespace Aura\Framework_Project\_Config;

use Aura\Di\Config;
use Aura\Di\Container;

class Common extends Config
{
    public function define(Container $di)
    {
        $di->set('aura/project-kernel:logger', $di->lazyNew('Monolog\Logger'));

        $di->params['Gallery\Models\Album\AlbumService'] = [
            'albumMapper' => $di->lazyNew("Gallery\Models\Album\AlbumMapper"),
            'albumModel' => $di->lazyNew("Gallery\Models\Album\AlbumModel"),
            'albumDataHandler' => $di->lazyNew("Gallery\Models\Album\AlbumDataHandler"),
            'filesystem' => $di->lazyNew("Filesystem\Filesystem"),
            'payloadFactory' => $di->lazyNew("FOA\DomainPayload\PayloadFactory"),
            'albumForm' => $di->lazyNew("Gallery\Input\AlbumForm"),
        ];

        $di->params['Gallery\Models\Album\AlbumDataHandler'] = [
            'albumModel' => $di->lazyNew("Gallery\Models\Album\AlbumModel"),
            'path' => ".." . DIRECTORY_SEPARATOR . "web" . DIRECTORY_SEPARATOR . "albums" . DIRECTORY_SEPARATOR,
        ];

        $di->params['Aura\Sql\ExtendedPdo'] = [
            'dsn' => "mysql:dbname=stoaj;host=127.0.0.1",
            'username' => "root",
            'password' => "root",
        ];

        $di->params['Html\Helper\Router'] = [
            'router' => $di->lazyGet('aura/web-kernel:router')
        ];
        $di->params['Aura\Html\HelperLocator']['map']["router"] = $di->lazyNew('Html\Helper\Router');

        /**
         * Config for FOA.Responder_Bundle
         */
        $di->params['FOA\Responder_Bundle\Renderer\AuraView'] = [
            "engine" => $di->lazyNew('Aura\View\View'),
        ];

        $di->params['FOA\Responder_Bundle\AbstractResponder'] = [
            "response" => $di->lazyGet('aura/web-kernel:response'),
            "accept" => $di->lazyNew('Aura\Accept\Accept'),
            "renderer" => $di->lazyNew('FOA\Responder_Bundle\Renderer\AuraView'),
        ];

        $di->params['Gallery\Actions\HelloBaby'] = array(
            'response' => $di->lazyGet('aura/web-kernel:response'),
        );
    }

    public function modify(Container $di)
    {
        $this->modifyLogger($di);
        $this->modifyCliDispatcher($di);
        $this->modifyWebRouter($di);
        $this->modifyWebDispatcher($di);
    }

    protected function modifyLogger(Container $di)
    {
        $project = $di->get('project');
        $mode = $project->getMode();
        $file = $project->getPath("tmp/log/{$mode}.log");

        $logger = $di->get('aura/project-kernel:logger');
        $logger->pushHandler($di->newInstance(
            'Monolog\Handler\StreamHandler',
            array(
                'stream' => $file,
            )
        ));
    }

    protected function modifyCliDispatcher(Container $di)
    {
        $context = $di->get('aura/cli-kernel:context');
        $stdio = $di->get('aura/cli-kernel:stdio');
        $logger = $di->get('aura/project-kernel:logger');
        $dispatcher = $di->get('aura/cli-kernel:dispatcher');
        $dispatcher->setObject(
            'hello',
            function ($name = 'World') use ($context, $stdio, $logger) {
                $stdio->outln("Hello {$name}!");
                $logger->debug("Said hello to '{$name}'");
            }
        );
    }

    public function modifyWebRouter(Container $di)
    {
        /** @var \Aura\Router\Router $router */
        $router = $di->get('aura/web-kernel:router');

        $router->add('hello', '/hello')
               ->setValues(array('action' => 'hello'));

        $router->add('hello_baby', '/baby')
               ->addValues(array('action' => 'hello_baby'));

        $router->add("gallery_root_albums", "/")
                ->addValues(["action" => "root_albums"]);

        $router->add("gallery_edit", "/gallery/edit")
            ->addValues(["action" => "gallery_edit"]);

        $router->add("gallery_edit_album_subalbums_image", "/edit/albums/images/{id}")
            ->addTokens(['id' => "\d+"])
            ->addValues(['action' => "gallery_edit_album_subalbums_image", ])
        ;

        /**
         * Images and Subalbums of certain album
         */

        $router->add("gallery_subalbums_and_images", "/gallery/albums/images/album/{id}")
                ->addTokens(["id" => "\d+"])
                ->addValues(["action" => "show_images_albums"]);

        $router->add("album_new_form", "/album/new")
                ->addServer(["REQUEST_METHOD" => "GET"])
                ->addValues(["action" => "album_new"]);

        $router->add("album_new", "/album/new")
                ->addServer(["REQUEST_METHOD" => "POST"])
                ->addValues(["action" => "album_create"]);
            


        $router->add("albums_edit_album", "/edit/album/{id}")
                ->addTokens(["id" => "\d+"])
                ->addValues(["action" => "albums_edit_album"]);

        $router->add("image_form_upload", "/image/form-upload")
                ->addValues(["action" => "image_form"]);

        $router->add("image_upload", "/upload/image")
                ->addServer(["REQUEST_METHOD" => "POST"])
                ->addValues(["action" => "image_upload"]);
    }

    /**
     * @param \Aura\Di\Container $di
     */
    public function modifyWebDispatcher($di)
    {
        /** @var \Aura\Dispatcher\Dispatcher $dispatcher */
        $dispatcher = $di->get('aura/web-kernel:dispatcher');

        $dispatcher->setObject('hello', function () use ($di) {
            $response = $di->get('aura/web-kernel:response');
            $response->content->set('Hello World!');
        });

        $dispatcher->setObject('hello_baby',
                                $di->lazyNew('Gallery\Actions\HelloBaby')
        );

        $dispatcher->setObject("root_albums",
                                $di->lazyNew("Gallery\Actions\Gallery\GalleryRootAlbumsAction")
        );

        $dispatcher->setObject("show_images_albums",
                                $di->lazyNew("Gallery\Actions\Gallery\GalleryShowImagesAlbumsAction")
        );

        $dispatcher->setObject("album_new",
                                $di->lazyNew("Gallery\Actions\Album\AlbumNewAction")
        );

        $dispatcher->setObject("album_create",
                                $di->lazyNew("Gallery\Actions\Album\AlbumCreateAction")
        );

        $dispatcher->setObject("gallery_edit",
                                $di->lazyNew("Gallery\Actions\Gallery\GalleryEditAction")
        );

        $dispatcher->setObject("gallery_edit_album_subalbums_image",
                                $di->lazyNew("Gallery\Actions\Gallery\GalleryEditAction")
        );

        $dispatcher->setObject("albums_edit_album",
                                $di->lazyNew("Gallery\Actions\Album\AlbumsEditAlbumAction")
        );

        $dispatcher->setObject("image_form",
                                $di->lazyNew("Gallery\Actions\Image\ImageFormCreationAction")
        );

        $dispatcher->setObject("image_upload",
                                $di->lazyNew("Gallery\Actions\Image\ImageUploadAction")
        );


    }
}