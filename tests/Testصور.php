<?php

namespace App\Tests\Controller;

use PHPUnit\Framework\TestCase;
use App\Controller\صورController;
use App\Repository\صورRepository;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class Testصور extends TestCase
{
    private $controller;
    private $repository;
    private $pdoMock;

    protected function setUp(): void
    {
        $this->repository = $this->createMock(صورRepository::class);
        $this->pdoMock = $this->createMock(\PDO::class);
        $this->controller = new صورController($this->repository);
    }

    public function testGetAll()
    {
        $this->pdoMock->expects($this->once())
            ->method('query')
            ->with('SELECT * FROM صور')
            ->willReturn($this->createMock(\PDOStatement::class));

        $request = new Request();
        $response = $this->controller->getAll($request);

        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testGetOne()
    {
        $id = 1;
        $this->pdoMock->expects($this->once())
            ->method('prepare')
            ->with('SELECT * FROM صور WHERE id = :id')
            ->willReturn($this->createMock(\PDOStatement::class));

        $request = new Request();
        $response = $this->controller->getOne($request, ['id' => $id]);

        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testCreate()
    {
        $data = ['name' => 'Test Image'];
        $this->pdoMock->expects($this->once())
            ->method('prepare')
            ->with('INSERT INTO صور (name) VALUES (:name)')
            ->willReturn($this->createMock(\PDOStatement::class));

        $request = new Request([], [], ['image' => $data]);
        $response = $this->controller->create($request);

        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals(201, $response->getStatusCode());
    }

    public function testUpdate()
    {
        $id = 1;
        $data = ['name' => 'Updated Image'];
        $this->pdoMock->expects($this->once())
            ->method('prepare')
            ->with('UPDATE صور SET name = :name WHERE id = :id')
            ->willReturn($this->createMock(\PDOStatement::class));

        $request = new Request([], [], ['image' => $data]);
        $response = $this->controller->update($request, ['id' => $id]);

        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testDelete()
    {
        $id = 1;
        $this->pdoMock->expects($this->once())
            ->method('prepare')
            ->with('DELETE FROM صور WHERE id = :id')
            ->willReturn($this->createMock(\PDOStatement::class));

        $request = new Request();
        $response = $this->controller->delete($request, ['id' => $id]);

        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals(200, $response->getStatusCode());
    }
}


Note: This test file assumes that the `صورController` class has methods `getAll`, `getOne`, `create`, `update`, and `delete` which handle the CRUD operations. The `صورRepository` class is also assumed to have methods that interact with the database. The test file uses mocking to simulate the database interactions.