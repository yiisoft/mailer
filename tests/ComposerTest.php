<?php
namespace Yiisoft\Mailer\Tests;

use Psr\EventDispatcher\EventDispatcherInterface;
use Psr\Log\LoggerInterface;
use Yiisoft\Mailer\Composer;
use Yiisoft\View\{Theme, View};

class ComposerTest extends TestCase
{
    /**
     * @return Composer $composer instance.
     */
    private function getComposer()
    {
        return $this->get(Composer::class);
    }

    public function testSetup()
    {
        $composer = $this->getComposer();
        $this->assertEquals($composer->getView(), $this->get(View::class));
        $this->assertEquals(realpath($composer->getViewPath()), realpath(__DIR__ . '/../views'));
    }

    public function testSetView()
    {
        $view = new View('/tmp/views', new Theme(), $this->get(EventDispatcherInterface::class), $this->get(LoggerInterface::class));
        $composer = $this->getComposer();
        $composer->setView($view);

        $this->assertEquals($composer->getView(), $view);
    }

    public function testSetViewPath()
    {
        $path = '/tmp/views';
        $composer = $this->getComposer();
        $composer->setViewPath($path);
        $this->assertEquals($composer->getViewPath(), $path);
    }

    public function testCreateTemplate()
    {
        $composer = $this->getComposer();
        $method = new \ReflectionMethod(Composer::class, 'createTemplate');
        $method->setAccessible(true);
        
        $viewName = 'test-view';
        /* @var $template Template */
        $template = $method->invoke($composer, $viewName);

        $this->assertSame($composer->getView(), $template->view);
        $this->assertEquals($viewName, $template->viewName);
        $this->assertEquals($composer->getViewPath(), $template->viewPath);
        $this->assertEquals($composer->htmlLayout, $template->htmlLayout);
        $this->assertEquals($composer->textLayout, $template->textLayout);
    }
}
