<?php

namespace Bitty\View;

use Bitty\View\AbstractView;
use Twig_Environment;
use Twig_ExtensionInterface;
use Twig_Loader_Filesystem;
use Twig_LoaderInterface;
use Twig_SimpleFilter;
use Twig_SimpleFunction;

/**
 * This acts as a very basic wrapper to implement the Twig templating engine.
 *
 * If more detailed customization is needed, you can access the Twig environment
 * and the loader directly using getEnvironment() and getLoader(), respectively.
 *
 * @see https://twig.symfony.com/
 */
class Twig extends AbstractView
{
    /**
     * @var Twig_LoaderInterface
     */
    protected $loader = null;

    /**
     * @var Twig_Environment
     */
    protected $environment = null;

    /**
     * @param string[]|string $paths
     * @param mixed[] $options
     */
    public function __construct($paths, array $options = [])
    {
        $this->loader = new Twig_Loader_Filesystem();

        if (is_string($paths)) {
            $this->loader->addPath($paths);
        } elseif (is_array($paths)) {
            foreach ($paths as $namespace => $path) {
                if (is_string($namespace)) {
                    $this->loader->addPath($path, $namespace);
                } else {
                    $this->loader->addPath($path);
                }
            }
        } else {
            throw new \InvalidArgumentException(
                sprintf(
                    'Path must be a string or an array; %s given.',
                    gettype($paths)
                )
            );
        }

        $this->environment = new Twig_Environment($this->loader, $options);
    }

    /**
     * {@inheritDoc}
     */
    public function render(string $template, $data = []): string
    {
        return $this->environment->load($template)->render($data);
    }

    /**
     * Renders a single block from a template using the given context data.
     *
     * @param string $template Template to render.
     * @param string $block Name of block in the template.
     * @param array $data Data to pass to template.
     *
     * @return string
     */
    public function renderBlock(string $template, string $block, array $data = []): string
    {
        return $this->environment->load($template)->renderBlock($block, $data);
    }

    /**
     * Adds a Twig extension.
     *
     * @param Twig_ExtensionInterface $extension
     */
    public function addExtension(Twig_ExtensionInterface $extension): void
    {
        $this->environment->addExtension($extension);
    }

    /**
     * Gets the Twig loader.
     *
     * This allows for direct manipulation of anything not already defined here.
     *
     * @return Twig_LoaderInterface
     */
    public function getLoader(): Twig_LoaderInterface
    {
        return $this->loader;
    }

    /**
     * Gets the Twig environment.
     *
     * This allows for direct manipulation of anything not already defined here.
     *
     * @return Twig_Environment
     */
    public function getEnvironment(): Twig_Environment
    {
        return $this->environment;
    }
}
