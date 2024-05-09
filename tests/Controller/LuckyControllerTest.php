<?php

namespace App\Controller;

use PHPUnit\Framework\TestCase;

/*
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Controller\ArgumentResolver;
use Symfony\Component\HttpKernel\Controller\ControllerResolver;
use Symfony\Component\Routing;
*/

/**
 * Test cases for cobtroller LuckyController.
 */
class LuckyControllerTest extends TestCase
{
    /**
     * Check if returns html page on number().
     */
    public function testNumber(): void
    {
        $luckyController = new LuckyController();
        $this->assertInstanceOf("\App\Controller\LuckyController", $luckyController);

        $res = $luckyController->number();
        $this->assertNotEmpty($res);
        // $res = json_encode($res);
        $resPart = "";

        if ($res->getContent()) {
            $resPart = substr($res->getContent(), 0, 6);
        }
        $this->assertEquals("<html>", $resPart);
    }

    /**
     * Check if returns html page on hiFunc().
     */
    public function testHiFunc(): void
    {
        $luckyController = new LuckyController();
        $this->assertInstanceOf("\App\Controller\LuckyController", $luckyController);

        $res = $luckyController->number();
        $this->assertNotEmpty($res);
        // $res = json_encode($res);
        $resPart = "";

        if ($res->getContent()) {
            $resPart = substr($res->getContent(), 0, 6);
        }
        $this->assertEquals("<html>", $resPart);
    }

    /*
    public function testControllerResponse(): void
    {
    $matcher = $this->createMock(Routing\Matcher\UrlMatcherInterface::class);

    $matcher
        ->expects($this->once())
        ->method('match')
        ->will($this->returnValue([
            '_route' => '/lucky/number'
        ]))
    ;
    $matcher
        ->expects($this->once())
        ->method('getContext')
        ->will($this->returnValue($this->createMock(Routing\RequestContext::class)))
    ;
    $controllerResolver = new ControllerResolver();
    $argumentResolver = new ArgumentResolver();

    $framework = new LuckyController($matcher, $controllerResolver, $argumentResolver);

    $response = $framework->handle(new Request());

    $this->assertEquals(200, $response->getStatusCode());
    }
    */
}
