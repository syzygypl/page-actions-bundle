<?php

namespace ArsThanea\PageActionsBundle\Entity;

use Doctrine\ORM\EntityRepository;
use Kunstmaan\NodeBundle\Entity\NodeTranslation;

class PageRouteRepository extends EntityRepository
{
    /**
     * @param string $url
     *
     * @return array
     */
    public function getCandidatesForUrl($url)
    {
        $query = $this->createQueryBuilder('pr')
            ->join('pr.nodeTranslation', 'nt')
            ->select('nt.id', 'nt.url', 'pr.actions')
            ->where('SUBSTRING(:url, 1, LENGTH(nt.url)) = nt.url')
            ->orderBy('LENGTH(nt.url)', "DESC")
            ->setParameter('url', trim($url, '/'))
            ->getQuery();

        return $query->getArrayResult();
    }

    /**
     * @param NodeTranslation $nodeTranslation
     */
    public function clearRoutesForNodeTranslation(NodeTranslation $nodeTranslation)
    {
        $this->getNodeTranslationRoutesQueryBuilder($nodeTranslation)->delete()->getQuery()->execute();
    }

    /**
     * @param NodeTranslation $nodeTranslation
     * @param array           $actions
     *
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function saveNodeTranslationActions(NodeTranslation $nodeTranslation, array $actions)
    {
        $route = $this->getNodeTranslationRoutesQueryBuilder($nodeTranslation)->getQuery()->getOneOrNullResult();

        if (null === $route) {
            $route = (new PageRoute)->setNodeTranslation($nodeTranslation);
        }

        $route->setActions($actions);

        $this->_em->persist($route);
        $this->_em->flush($route);

    }

    /**
     * @param NodeTranslation $nodeTranslation
     *
     * @return \Doctrine\ORM\QueryBuilder
     */
    private function getNodeTranslationRoutesQueryBuilder(NodeTranslation $nodeTranslation)
    {
        return $this->createQueryBuilder('pr')
            ->where('pr.nodeTranslation = :nodeTranslation')
            ->setParameter('nodeTranslation', $nodeTranslation);
    }
}
