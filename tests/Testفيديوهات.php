<?php

namespace App\Tests\Controller;

use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Client;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use PHPUnit\Framework\MockObject\MockObject;
use PDO;

class Testفيديوهات extends TestCase
{
    private $client;

    protected function setUp(): void
    {
        $this->client = new Client();
    }

    public function testGetVideos(): void
    {
        $pdoMock = $this->createMock(PDO::class);
        $pdoMock->expects($this->once())
            ->method('query')
            ->with('SELECT * FROM videos')
            ->willReturn($pdoMock);

        $this->client->request('GET', '/api/videos', [], [], ['PDO' => $pdoMock]);

        $response = $this->client->getResponse();
        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
    }

    public function testPostVideo(): void
    {
        $pdoMock = $this->createMock(PDO::class);
        $pdoMock->expects($this->once())
            ->method('prepare')
            ->with('INSERT INTO videos (title, description) VALUES (:title, :description)')
            ->willReturn($pdoMock);
        $pdoMock->expects($this->once())
            ->method('execute')
            ->with(['title' => 'Test Video', 'description' => 'Test Description']);

        $request = new Request([], [], ['title' => 'Test Video', 'description' => 'Test Description']);
        $this->client->request('POST', '/api/videos', [], [], ['PDO' => $pdoMock], $request);

        $response = $this->client->getResponse();
        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(Response::HTTP_CREATED, $response->getStatusCode());
    }

    public function testPutVideo(): void
    {
        $pdoMock = $this->createMock(PDO::class);
        $pdoMock->expects($this->once())
            ->method('prepare')
            ->with('UPDATE videos SET title = :title, description = :description WHERE id = :id')
            ->willReturn($pdoMock);
        $pdoMock->expects($this->once())
            ->method('execute')
            ->with(['title' => 'Updated Video', 'description' => 'Updated Description', 'id' => 1]);

        $request = new Request([], [], ['id' => 1, 'title' => 'Updated Video', 'description' => 'Updated Description']);
        $this->client->request('PUT', '/api/videos/1', [], [], ['PDO' => $pdoMock], $request);

        $response = $this->client->getResponse();
        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
    }

    public function testDeleteVideo(): void
    {
        $pdoMock = $this->createMock(PDO::class);
        $pdoMock->expects($this->once())
            ->method('prepare')
            ->with('DELETE FROM videos WHERE id = :id')
            ->willReturn($pdoMock);
        $pdoMock->expects($this->once())
            ->method('execute')
            ->with(['id' => 1]);

        $this->client->request('DELETE', '/api/videos/1', [], [], ['PDO' => $pdoMock]);

        $response = $this->client->getResponse();
        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(Response::HTTP_NO_CONTENT, $response->getStatusCode());
    }
}