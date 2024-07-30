<?php

declare(strict_types=1);

namespace Hollow3464\TwigViteExtension;

use CuyZ\Valinor\MapperBuilder;
use League\Flysystem\Filesystem;

final class ViteManifestMapperFlysystemLoader implements ViteManifestLoaderInterface
{
    public function __construct(private Filesystem $storage) {}

    /**
     * {@inheritdoc}
     */
    public function load(string $path, string $entry): ViteManifest
    {
        $manifestData = $this->storage->read($path);

        /** @var array<string,array<string,mixed>>|null */
        $manifest = json_decode($manifestData, true);
        if (! is_array($manifest)) {
            throw new \Exception('Manifest not found');
        }

        $entry = $manifest[$entry] ?? null;
        if (! $entry) {
            throw new \Exception("No entry found for {$entry}");
        }

        return (new MapperBuilder())
            ->allowSuperfluousKeys()
            ->allowPermissiveTypes()
            ->enableFlexibleCasting()
            ->mapper()
            ->map(ViteManifest::class, $entry);
    }
}
