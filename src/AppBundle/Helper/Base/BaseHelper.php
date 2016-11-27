<?php

namespace AppBundle\Helper\Base;

use Symfony\Component\DependencyInjection\ContainerAwareTrait;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class BaseHelper.
 */
abstract class BaseHelper
{
    use ContainerAwareTrait;

    /**
     * Returns container.
     *
     * @return ContainerInterface
     */
    public function getContainer()
    {
        return $this->container;
    }
}
