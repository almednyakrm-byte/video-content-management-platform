<?php

namespace App\Tests\Controller;

use App\Controller\ContentController;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Paginator\PaginationInterface;
use Symfony\Component\Paginator\PaginatorInterface;

class Testمحتوى-إلكتروني extends TestCase
{
    private $controller;
    private $pdoMock;

    protected function setUp(): void
    {
        $this->pdoMock = $this->createMock('PDO');
        $this->controller = new ContentController($this->pdoMock);
    }

    public function testGetContent(): void
    {
        $this->pdoMock->expects($this->once())
            ->method('query')
            ->with('SELECT * FROM content')
            ->willReturn($this->createMock('PDOStatement'));

        $response = $this->controller->getContent();
        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
    }

    public function testGetContentById(): void
    {
        $id = 1;
        $this->pdoMock->expects($this->once())
            ->method('query')
            ->with('SELECT * FROM content WHERE id = ?', [$id])
            ->willReturn($this->createMock('PDOStatement'));

        $response = $this->controller->getContent($id);
        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
    }

    public function testGetContentByIdNotFound(): void
    {
        $id = 1;
        $this->pdoMock->expects($this->once())
            ->method('query')
            ->with('SELECT * FROM content WHERE id = ?', [$id])
            ->willReturn($this->createMock('PDOStatement'));

        $this->expectException(NotFoundHttpException::class);
        $this->controller->getContent($id);
    }

    public function testCreateContent(): void
    {
        $request = new Request();
        $request->request->set('title', 'Test Title');
        $request->request->set('description', 'Test Description');

        $this->pdoMock->expects($this->once())
            ->method('prepare')
            ->with('INSERT INTO content (title, description) VALUES (?, ?)')
            ->willReturn($this->createMock('PDOStatement'));

        $response = $this->controller->createContent($request);
        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals(Response::HTTP_CREATED, $response->getStatusCode());
    }

    public function testUpdateContent(): void
    {
        $id = 1;
        $request = new Request();
        $request->request->set('title', 'Test Title');
        $request->request->set('description', 'Test Description');

        $this->pdoMock->expects($this->once())
            ->method('prepare')
            ->with('UPDATE content SET title = ?, description = ? WHERE id = ?', [$request->request->get('title'), $request->request->get('description'), $id])
            ->willReturn($this->createMock('PDOStatement'));

        $response = $this->controller->updateContent($id, $request);
        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
    }

    public function testDeleteContent(): void
    {
        $id = 1;

        $this->pdoMock->expects($this->once())
            ->method('prepare')
            ->with('DELETE FROM content WHERE id = ?', [$id])
            ->willReturn($this->createMock('PDOStatement'));

        $response = $this->controller->deleteContent($id);
        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals(Response::HTTP_NO_CONTENT, $response->getStatusCode());
    }
}