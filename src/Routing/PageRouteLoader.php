<?php

namespace ArsThanea\PageActionsBundle\Routing;

use Symfony\Component\Config\Loader\Loader;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;

class PageRouteLoader extends Loader
{
    const TYPE = 'page_actions';

    const PATH_PREFIX = '/_page_actions';

    /**
     * @var array
     */
    private $resources;

    /**
     * PageRouteLoader constructor.
     *
     * @param array $resources
     */
    public function __construct(array $resources)
    {
        $this->resources = $resources;
    }

    /**
     * Loads a resource.
     *
     * @param mixed       $resource The resource
     * @param string|null $type     The resource type or null if unknown
     *
     * @return RouteCollection
     * @throws \Exception If something went wrong
     */
    public function load($resource, $type = null)
    {
        $collection = new RouteCollection();

        foreach ($this->resources as $name => $resource) {
            /** @var RouteCollection|Route[] $subCollection */
            $subCollection = $this->import($resource['resource'], $resource['type']);

            // those are used for matching only:
            $subCollection->addDefaults(['page_action' => true]);
            $subCollection->setCondition(sprintf(
                'context.hasParameter("page_actions") and "%s" in context.getParameter("page_actions")',
                $name
            ));

            foreach ($subCollection as $routeName => $route) {
                $route = clone $route;
                $collection->add(sprintf('page_action_%s', $routeName), $route->setPath(self::PATH_PREFIX . $route->getPath()));
            }

            $collection->addCollection($subCollection);

            // those are used to generate urls:
            foreach ($subCollection as $routeName => $route) {
                $route = clone $route;
                $route->setCondition('1 > 2')->setPath('/{url}' . $route->getPath())->addRequirements(['url' => '.+']);
                $collection->add($routeName, $route);
            }

        }

        return $collection;

    }

    /**
     * Returns whether this class supports the given resource.
     *
     * @param mixed       $resource A resource
     * @param string|null $type     The resource type or null if unknown
     *
     * @return bool True if this class supports the given resource, false otherwise
     */
    public function supports($resource, $type = null)
    {
        return $type === self::TYPE;
    }

}
