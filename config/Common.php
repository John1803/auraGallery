<?php
namespace Aura\Framework_Project\_Config;

use Aura\Accept\Accept;
use Aura\Di\Config;
use Aura\Di\Container;
use Aura\Html\HelperLocator;
use Aura\Sql\ExtendedPdo;
use Aura\View\View;
use Filesystem\Filesystem;
use FOA\DomainPayload\PayloadFactory;
use FOA\Responder_Bundle\AbstractResponder;
use FOA\Responder_Bundle\Renderer\AuraView;
use Gallery\Actions\Album\AlbumCreateAction;
use Gallery\Actions\Album\AlbumsEditAlbumAction;
use Gallery\Actions\Gallery\GalleryEditAction;
use Gallery\Actions\Gallery\GalleryRootAlbumsAction;
use Gallery\Actions\Gallery\GalleryShowImagesAlbumsAction;
use Gallery\Actions\Image\ImageNewAction;
use Gallery\Actions\Image\ImageUploadAction;
use Gallery\Input\AlbumForm;
use Gallery\Models\Album\AlbumDataHandler;
use Gallery\Models\Album\AlbumMapper;
use Gallery\Models\Album\AlbumModel;
use Gallery\Models\Album\AlbumService;
use Html\Helper\Router;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;

class Common extends Config
{
    public function define(Container $di)
    {
        $di->set('aura/project-kernel:logger', $di->lazyNew(Logger::class));

        $di->params[AlbumDataHandler::class] = [
            'albumModel' => $di->lazyNew(AlbumModel::class),
            'path' => '..' . DIRECTORY_SEPARATOR . 'web' . DIRECTORY_SEPARATOR . 'albums' . DIRECTORY_SEPARATOR,
        ];

        $di->params[AlbumService::class] = [
            'albumMapper' => $di->lazyNew(AlbumMapper::class),
            'albumModel' => $di->lazyNew(AlbumModel::class),
            'albumDataHandler' => $di->lazyNew(AlbumDataHandler::class),
            'filesystem' => $di->lazyNew(Filesystem::class),
            'payloadFactory' => $di->lazyNew(PayloadFactory::class),
            'albumForm' => $di->lazyNew(AlbumForm::class),
        ];

        $di->params[ExtendedPdo::class] = [
            'dsn' => 'mysql:dbname=stoaj;host=127.0.0.1',
            'username' => 'root',
            'password' => 'root',
        ];

        $di->params[Router::class] = [
            'router' => $di->lazyGet('aura/web-kernel:router')
        ];
        $di->params[HelperLocator::class]['map']['router'] = $di->lazyNew(Router::class);

        /**
         * Config for FOA.Responder_Bundle
         */
        $di->params[AuraView::class] = [
            'engine' => $di->lazyNew(View::class),
        ];

        $di->params[AbstractResponder::class] = [
            'response' => $di->lazyGet('aura/web-kernel:response'),
            'accept' => $di->lazyNew(Accept::class),
            'renderer' => $di->lazyNew(AuraView::class),
        ];

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
            StreamHandler::class,
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

        $router->add('gallery_root_albums', '/')
                ->addValues(['action' => 'root_albums']);

        $router->add('gallery_edit', '/gallery/edit')
            ->addValues(['action' => 'gallery_edit']);

        $router->add('gallery_edit_album_subalbums_image', '/edit/albums/images/{id}')
            ->addTokens(['id' => '\d+'])
            ->addValues(['action' => 'gallery_edit_album_subalbums_image', ])
        ;

        /**
         * Images and Subalbums of certain album
         */

        $router->add('gallery_subalbums_and_images', '/gallery/albums/images/album/{id}')
                ->addTokens(['id' => '\d+'])
                ->addValues(['action' => 'show_images_albums']);

        $router->add('album_new_form', '/album/new')
                ->addServer(['REQUEST_METHOD' => 'GET'])
                ->addValues(['action' => 'album_new']);

        $router->add('album_new', '/album/new')
                ->addServer(['REQUEST_METHOD' => 'POST'])
                ->addValues(['action' => 'album_create']);

        $router->add('albums_edit_album', '/edit/album/{id}')
                ->addTokens(['id' => '\d+'])
                ->addValues(['action' => 'albums_edit_album']);

/****************************************** Image uploading routes ****************************************************/

        $router->add('images_form_upload', '/upload/images-form')
                ->addValues(['action' => 'images_new']);

        $router->add('images_upload', '/upload/images')
                ->addServer(['REQUEST_METHOD' => 'POST'])
                ->addValues(['action' => 'images_upload']);
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

        $dispatcher->setObject(
            'root_albums',
                                $di->lazyNew(GalleryRootAlbumsAction::class)
        );

        $dispatcher->setObject('show_images_albums',
                                $di->lazyNew(GalleryShowImagesAlbumsAction::class)
        );

        $dispatcher->setObject('album_create',
                                $di->lazyNew(AlbumCreateAction::class)
        );

        $dispatcher->setObject('gallery_edit',
                                $di->lazyNew(GalleryEditAction::class)
        );

        $dispatcher->setObject('gallery_edit_album_subalbums_image',
                                $di->lazyNew(GalleryEditAction::class)
        );

        $dispatcher->setObject('albums_edit_album',
                                $di->lazyNew(AlbumsEditAlbumAction::class)
        );

/*********************************** Image uploading dispatchable actions *********************************************/

        $dispatcher->setObject('images_new',
                                $di->lazyNew(ImageNewAction::class)
        );

        $dispatcher->setObject('images_upload',
                                $di->lazyNew(ImageUploadAction::class)
        );
    }
}
