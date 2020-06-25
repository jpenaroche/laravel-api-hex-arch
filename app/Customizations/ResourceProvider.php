<?php
/**
 * Created by PhpStorm.
 * User: penna
 * Date: 3/9/19
 * Time: 9:24 AM
 */

namespace App\Customizations;

use Illuminate\Routing\ResourceRegistrar;

class ResourceProvider extends ResourceRegistrar
{
    // add data to the array
    /**
     * The default actions for a resourceful controller.
     *
     * @var array
     */
    protected $resourceDefaults = ['index', 'store', 'show', 'update', 'destroy', 'toggle','medias', 'order'];


    /**
     * Add the data method for a resourceful route.
     *
     * @param  string $name
     * @param  string $base
     * @param  string $controller
     * @param  array $options
     * @return \Illuminate\Routing\Route
     */
    protected function addResourceToggle($name, $base, $controller, $options)
    {
        $uri = $this->getResourceUri($name) . '/toggle/{field}';

        $action = $this->getResourceAction($name, $controller, 'toggle', $options);

        return $this->router->put($uri, $action);
    }

    /**
     * Add the data method for a resourceful route.
     *
     * @param  string $name
     * @param  string $base
     * @param  string $controller
     * @param  array $options
     * @return \Illuminate\Routing\Route
     */
    protected function addResourceMedias($name, $base, $controller, $options)
    {
        $uri = $this->getResourceUri($name) . '/medias/{id}';

        $action = $this->getResourceAction($name, $controller, 'medias', $options);

        return $this->router->get($uri, $action);
    }

    /**
     * Add the data method for a resourceful route.
     *
     * @param  string $name
     * @param  string $base
     * @param  string $controller
     * @param  array $options
     * @return \Illuminate\Routing\Route
     */
    protected function addResourceUpdate($name, $base, $controller, $options)
    {
        $uri = $this->getResourceUri($name) . '/{id}';

        $action = $this->getResourceAction($name, $controller, 'update', $options);

        return $this->router->post($uri, $action);
    }

    /**
     * Add the data method for a resourceful route.
     *
     * @param  string $name
     * @param  string $base
     * @param  string $controller
     * @param  array $options
     * @return \Illuminate\Routing\Route
     */
    protected function addResourceOrder($name, $base, $controller, $options)
    {
        $uri = $this->getResourceUri($name.'/order');

        $action = $this->getResourceAction($name, $controller, 'order', $options);

        return $this->router->patch($uri, $action);
    }

}