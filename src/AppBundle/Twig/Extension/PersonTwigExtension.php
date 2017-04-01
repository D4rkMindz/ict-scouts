<?php

namespace AppBundle\Twig\Extension;

class PersonTwigExtension extends \Twig_Extension
{
    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'brokerstar_common.twig_extension.array';
    }

    /**
     * {@inheritdoc}
     */
    public function getFilters()
    {
        return [
            new \Twig_SimpleFilter('calculateAge', [$this, 'calculateAge']),
        ];
    }

    public function calculateAge(\DateTime $value)
    {
        return (new \DateTime('today'))->diff($value)->format('%y Jahre');
    }
}
