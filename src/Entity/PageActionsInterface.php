<?php

namespace ArsThanea\PageActionsBundle\Entity;

interface PageActionsInterface
{
    /**
     * List of strings representing routing resources defined in configuration
     *
     * @return array
     */
    public function getPageActions();

}
