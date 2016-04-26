<?php

namespace ArsThanea\PageActionsBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Kunstmaan\NodeBundle\Entity\NodeTranslation;


/**
 * @ORM\Entity(repositoryClass="PageRouteRepository")
 * @ORM\Table(name="page_routes")
 */
class PageRoute
{

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var NodeTranslation
     *
     * @ORM\OneToOne(targetEntity="Kunstmaan\NodeBundle\Entity\NodeTranslation")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="node_translation_id", referencedColumnName="id", nullable=false)
     * })
     */
    private $nodeTranslation;

    /**
     * @var string
     * @ORM\Column(type="string", nullable=false)
     */
    private $url;

    /**
     * @var array
     *
     * @ORM\Column(name="actions", type="simple_array", nullable=false)
     */
    private $actions;


    public function getId()
    {
        return $this->id;
    }

    /**
     * @return NodeTranslation
     */
    public function getNodeTranslation()
    {
        return $this->nodeTranslation;
    }

    /**
     * @param NodeTranslation $nodeTranslation
     *
     * @return $this
     */
    public function setNodeTranslation(NodeTranslation $nodeTranslation)
    {
        $this->nodeTranslation = $nodeTranslation;

        return $this;
    }

    /**
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @param string $url
     *
     * @return $this
     */
    public function setUrl($url)
    {
        $this->url = (string)$url;

        return $this;
    }

    /**
     * @return array
     */
    public function getActions()
    {
        return $this->actions;
    }

    /**
     * @param array $actions
     *
     * @return $this
     */
    public function setActions(array $actions)
    {
        $this->actions = $actions;

        return $this;
    }

}
