<?php
declare(strict_types=1);

namespace Ronald2Wing\Forum\Test\Unit\Helper;

use PHPUnit\Framework\TestCase;
use Ronald2Wing\Forum\Helper\Params;
use Ronald2Wing\Forum\Helper\Constant;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Session\SessionManagerInterface;

class ParamsTest extends TestCase
{
    private Params $params;
    private RequestInterface $request;
    private SessionManagerInterface $session;

    protected function setUp(): void
    {
        $this->request = $this->createMock(RequestInterface::class);
        $this->session = $this->createMock(SessionManagerInterface::class);
        $this->params = new Params($this->request, $this->session);
    }

    public function testGetLimitFromRequest(): void
    {
        $this->request->method('getParam')->with('limit')->willReturn('25');
        $this->session->expects($this->once())
            ->method('setData')
            ->with('forum_limit_forum', 25);

        $result = $this->params->getLimit('forum', 10);
        $this->assertSame(25, $result);
    }

    public function testGetLimitFromSession(): void
    {
        $this->request->method('getParam')->with('limit')->willReturn(null);
        $this->session->method('getData')
            ->with('forum_limit_forum')
            ->willReturn(15);

        $result = $this->params->getLimit('forum', 10);
        $this->assertSame(15, $result);
    }

    public function testGetLimitDefault(): void
    {
        $this->request->method('getParam')->with('limit')->willReturn(null);
        $this->session->method('getData')
            ->with('forum_limit_topic')
            ->willReturn(null);

        $result = $this->params->getLimit('topic', 20);
        $this->assertSame(20, $result);
    }

    public function testGetLimitZeroFallsBackToSession(): void
    {
        $this->request->method('getParam')->with('limit')->willReturn('0');
        $this->session->expects($this->never())->method('setData');
        $this->session->method('getData')
            ->with('forum_limit_search')
            ->willReturn(30);

        $result = $this->params->getLimit('search', 10);
        $this->assertSame(30, $result);
    }

    public function testGetLimitZeroFallsBackToDefault(): void
    {
        $this->request->method('getParam')->with('limit')->willReturn('0');
        $this->session->method('getData')
            ->with('forum_limit_search')
            ->willReturn(null);

        $result = $this->params->getLimit('search', 10);
        $this->assertSame(10, $result);
    }

    public function testGetSortFromRequest(): void
    {
        $this->request->method('getParam')->with('sort')->willReturn('title_asc');
        $this->session->expects($this->once())
            ->method('setData')
            ->with('forum_sort_forum', 'title_asc');

        $result = $this->params->getSort('forum');
        $this->assertSame('title_asc', $result);
    }

    public function testGetSortFromSession(): void
    {
        $this->request->method('getParam')->with('sort')->willReturn(null);
        $this->session->method('getData')
            ->with('forum_sort_topic')
            ->willReturn('created_at_desc');

        $result = $this->params->getSort('topic');
        $this->assertSame('created_at_desc', $result);
    }

    public function testGetSortDefault(): void
    {
        $this->request->method('getParam')->with('sort')->willReturn(null);
        $this->session->method('getData')
            ->with('forum_sort_post')
            ->willReturn(null);

        $result = $this->params->getSort('post');
        $this->assertSame(Constant::SORT_CREATED_DESC, $result);
    }

    public function testGetSortEmptyStringFallsBackToSession(): void
    {
        $this->request->method('getParam')->with('sort')->willReturn('');
        $this->session->expects($this->never())->method('setData');
        $this->session->method('getData')
            ->with('forum_sort_forum')
            ->willReturn('title_desc');

        $result = $this->params->getSort('forum');
        $this->assertSame('title_desc', $result);
    }

    public function testGetPageFromRequest(): void
    {
        $this->request->method('getParam')->with('p')->willReturn('3');
        $this->session->expects($this->once())
            ->method('setData')
            ->with('forum_page_forum', 3);

        $result = $this->params->getPage('forum');
        $this->assertSame(3, $result);
    }

    public function testGetPageFromSession(): void
    {
        $this->request->method('getParam')->with('p')->willReturn(null);
        $this->session->method('getData')
            ->with('forum_page_topic')
            ->willReturn(5);

        $result = $this->params->getPage('topic');
        $this->assertSame(5, $result);
    }

    public function testGetPageDefault(): void
    {
        $this->request->method('getParam')->with('p')->willReturn(null);
        $this->session->method('getData')
            ->with('forum_page_post')
            ->willReturn(null);

        $result = $this->params->getPage('post');
        $this->assertSame(1, $result);
    }

    public function testGetPageZeroFallsBackToSession(): void
    {
        $this->request->method('getParam')->with('p')->willReturn('0');
        $this->session->expects($this->never())->method('setData');
        $this->session->method('getData')
            ->with('forum_page_forum')
            ->willReturn(2);

        $result = $this->params->getPage('forum');
        $this->assertSame(2, $result);
    }

    public function testGetPageZeroFallsBackToDefault(): void
    {
        $this->request->method('getParam')->with('p')->willReturn('0');
        $this->session->method('getData')
            ->with('forum_page_forum')
            ->willReturn(null);

        $result = $this->params->getPage('forum');
        $this->assertSame(1, $result);
    }

    public function testGetRequest(): void
    {
        $returned = $this->params->getRequest();
        $this->assertSame($this->request, $returned);
    }
}
