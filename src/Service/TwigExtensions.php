<?php

namespace App\Service;

use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class TwigExtensions extends AbstractExtension
{
    protected $params;

    public function getFunctions()
    {
        return [
            new TwigFunction('get_parameter', array($this, 'getParameter'))
        ];
    }

    public function __construct(ParameterBagInterface $params)
    {
        $this->params = $params;
    }

    public function getParameter($parameter)
    {
        return $this->params->get($parameter);
    }

    public function getName()
    {
        return 'TwigExtensions';
    }
}