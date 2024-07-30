<?php

namespace Hollow3464\TwigViteExtension;

interface ViteManifestLoaderInterface
{
    /**
     * @throws \Exception
     */
    public function load(string $pathm, string $entry): ViteManifest;
}
