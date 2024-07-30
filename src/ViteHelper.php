<?php

namespace Hollow3464\TwigViteExtension;

use Exception;

final class ViteHelper
{
    public function __construct(
        private ViteManifestLoaderInterface $loader,
        private string $entry = 'src/main.ts',
        private string $publicDir = '/dist',
    ) {}

    public function vite(): string
    {
        $outputs = [
            $this->jsPreloadImports(),
            $this->cssTag(),
            $this->jsTag(),
        ];

        return implode("\n", $outputs);
    }

    public function jsTag(): string
    {
        $url = $this->assetUrl();

        if (! $url) {
            return '';
        }

        return "<script src='$url'></script>";
    }

    public function jsPreloadImports(): string
    {
        $res = '';
        foreach ($this->importsUrls() as $url) {
            $res .= "<link rel='modulepreload' href='$url' />";
        }

        return $res;
    }

    public function cssTag(): string
    {
        $tags = '';
        foreach ($this->cssUrls() as $url) {
            $tags .= "<link rel='stylesheet' href='$url' />";
        }

        return $tags;
    }

    /**
     * Read Vite Manifest
     *
     * @throws Exception
     */
    public function getManifest(): ViteManifest
    {
        try {
            return $this->loader->load(sprintf('%s/.vite/manifest.json', $this->publicDir), $this->entry);
        } catch (\Exception) {
            try {
                return $this->loader->load(sprintf('%s/manifest.json', $this->publicDir), $this->entry);
            } catch (\Exception) {
                throw new \Exception('Manifest not found');
            }
        }
    }

    public function assetUrl(): string
    {
        $manifest = $this->getManifest();

        return sprintf('%s/%s', $this->publicDir, $manifest->file);
    }

    /** @return array<string> */
    public function importsUrls(): array
    {
        $urls = [];
        $manifest = $this->getManifest();

        foreach ($manifest->imports as $import) {
            $urls[] = sprintf('%s/%s', $this->publicDir, $import->file);
        }

        return $urls;
    }

    /**
     * @return array<string>
     */
    public function cssUrls(): array
    {
        $urls = [];
        $manifest = $this->getManifest();

        foreach ($manifest->css as $styles) {
            $urls[] = sprintf('%s/%s', $this->publicDir, $styles);
        }

        return $urls;
    }
}
