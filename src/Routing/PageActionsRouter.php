<?php

namespace ArsThanea\PageActionsBundle\Routing;

use ArsThanea\PageActionsBundle\Entity\PageRouteRepository;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;
use Symfony\Component\Routing\Exception\RouteNotFoundException;
use Symfony\Component\Routing\RequestContext;
use Symfony\Component\Routing\RouteCollection;
use Symfony\Component\Routing\RouterInterface;

class PageActionsRouter implements RouterInterface
{

    /**
     * @var PageRouteRepository
     */
    private $repository;

    /**
     * @var RouterInterface
     */
    private $router;

    /**
     * @var RequestContext
     */
    private $context;

    /**
     * @param PageRouteRepository $repository
     * @param RouterInterface     $router
     */
    public function __construct(PageRouteRepository $repository, RouterInterface $router)
    {
        $this->repository = $repository;
        $this->router = $router;
    }

    /**
     * @inheritDoc
     */
    public function setContext(RequestContext $context)
    {
        $this->context = $context;
    }

    /**
     * @inheritDoc
     */
    public function getContext()
    {
        return $this->context;
    }

    /**
     * @inheritDoc
     */
    public function getRouteCollection()
    {
        return new RouteCollection();
    }

    /**
     * @@inheritDoc
     */
    public function generate($name, $parameters = [], $referenceType = self::ABSOLUTE_PATH)
    {
        throw new RouteNotFoundException;
    }

    /**
     * @inheritDoc
     */
    public function match($pathinfo)
    {
        if ($this->context->hasParameter('page_actions') || "/_" === substr($pathinfo, 0, 2)) {
            // some other router before this one should match something:
            throw new ResourceNotFoundException;
        }

        $candidates = $this->repository->getCandidatesForUrl($pathinfo);

        foreach ($candidates as $route) {
            $this->getContext()->setParameter('page_actions', $route['actions']);

            try {
                $res = $this->router->match(substr($pathinfo, strlen($route['url'])));

                if ($res) {
                    $res['_nodeTranslation'] = $route['id'];

                    return $res;
                }

            } catch (ResourceNotFoundException $e) {
                // noop. try another candidate
            }

        }

        throw new ResourceNotFoundException;
    }

}
