<?php

namespace Hollow3464\TwigViteExtension;

final class ViteManifest
{
    /**
     * @param  array<string>  $css
     * @param  array<string, ViteManifestImport>  $imports
     */
    public function __construct(
        public string $src,
        public string $file,
        public bool $isEntry,
        public array $css = [],
        public array $imports = []
    ) {}
}
