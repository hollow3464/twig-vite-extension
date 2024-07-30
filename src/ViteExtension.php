<?php

namespace Hollow3464\TwigViteExtension;

use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

final class ViteTwigExtension extends AbstractExtension
{
    public function __construct(
        private readonly ViteHelper $viteHelper
    ) {}

    public function getFunctions()
    {
        return [new TwigFunction(
            name: 'vite',
            callable: $this->viteHelper->vite(...),
            options: ['is_safe' => ['html']]
        )];
    }
}
