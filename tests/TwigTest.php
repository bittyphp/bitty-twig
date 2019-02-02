<?php

namespace Bitty\Tests\View;

use Bitty\View\AbstractView;
use Bitty\View\Twig;
use PHPUnit\Framework\TestCase;
use Twig_Environment;
use Twig_ExtensionInterface;
use Twig_Loader_Filesystem;

class TwigTest extends TestCase
{
    /**
     * @var Twig
     */
    protected $fixture = null;

    protected function setUp(): void
    {
        parent::setUp();

        $this->fixture = new Twig(__DIR__.'/templates/');
    }

    public function testInstanceOf(): void
    {
        self::assertInstanceOf(AbstractView::class, $this->fixture);
    }

    /**
     * @param mixed $paths
     * @param string $expected
     *
     * @dataProvider sampleInvalidPaths
     */
    public function testInvalidPaths($paths, string $expected): void
    {
        self::expectException(\InvalidArgumentException::class);
        self::expectExceptionMessage('Path must be a string or an array; '.$expected.' given.');

        new Twig($paths);
    }

    public function sampleInvalidPaths(): array
    {
        return [
            'null' => [null, 'NULL'],
            'object' => [(object) [], 'object'],
            'false' => [false, 'boolean'],
            'true' => [true, 'boolean'],
            'int' => [rand(), 'integer'],
        ];
    }

    /**
     * @param string $template
     * @param array $data
     * @param string $expected
     *
     * @dataProvider sampleRender
     */
    public function testRender(string $template, array $data, string $expected): void
    {
        $this->fixture = new Twig(
            [
                __DIR__.'/templates/',
                'test' => __DIR__.'/templates/parent/',
            ]
        );

        $actual = $this->fixture->render($template, $data);

        self::assertEquals($expected, $actual);
    }

    public function sampleRender(): array
    {
        $name = uniqid('name');

        return [
            'simple' => [
                'template' => 'test.html.twig',
                'data' => ['name' => $name],
                'expected' => 'Hello, '.$name.PHP_EOL.PHP_EOL.'Goodbye, '.$name.PHP_EOL,
            ],
            'nested' => [
                'template' => 'parent/test.html.twig',
                'data' => ['name' => $name],
                'expected' => 'Hello, '.$name.', from parent'.PHP_EOL,
            ],
            'multiple nested' => [
                'template' => 'parent/child/test.html.twig',
                'data' => ['name' => $name],
                'expected' => 'Hello, '.$name.', from parent/child'.PHP_EOL,
            ],
            'namespaced' => [
                'template' => '@test/test.html.twig',
                'data' => ['name' => $name],
                'expected' => 'Hello, '.$name.', from parent'.PHP_EOL,
            ],
        ];
    }

    public function testRenderBlock(): void
    {
        $name = uniqid('name');

        $actual = $this->fixture->renderBlock('test.html.twig', 'hello', ['name' => $name]);

        self::assertEquals('Hello, '.$name.PHP_EOL, $actual);
    }

    public function testAddExtension(): void
    {
        $extension = $this->createMock(Twig_ExtensionInterface::class);

        $this->fixture->addExtension($extension);

        $actual = $this->fixture->getEnvironment()->getExtensions();

        $last = end($actual);
        self::assertSame($extension, $last);
    }

    public function testGetLoader(): void
    {
        $actual = $this->fixture->getLoader();

        self::assertInstanceOf(Twig_Loader_Filesystem::class, $actual);
    }

    public function testGetEnvironment(): void
    {
        $actual = $this->fixture->getEnvironment();

        self::assertInstanceOf(Twig_Environment::class, $actual);
    }
}
