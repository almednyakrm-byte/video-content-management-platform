<?php

namespace App\Tests\Unit\Auth;

use App\Auth\AuthService;
use App\Auth\AuthRepository;
use App\Auth\User;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\MockObject\MockBuilder;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DriverManager;

class TestAuth extends TestCase
{
    private $authService;
    private $authRepository;
    private $connection;

    protected function setUp(): void
    {
        $this->connection = $this->createMock(Connection::class);
        $this->authRepository = $this->createMock(AuthRepository::class);
        $this->authService = new AuthService($this->authRepository);

        $this->connection->method('connect')->willReturn($this->connection);
        $this->connection->method('fetchAll')->willReturn([
            ['id' => 1, 'username' => 'testuser', 'password' => 'testpassword'],
        ]);
        $this->authRepository->method('getUser')->willReturn(new User(1, 'testuser', 'testpassword'));
    }

    public function testLoginSuccess()
    {
        $username = 'testuser';
        $password = 'testpassword';

        $this->authRepository->method('getUser')->with($username, $password)->willReturn(new User(1, $username, $password));

        $result = $this->authService->login($username, $password);

        $this->assertTrue($result);
    }

    public function testLoginFailure()
    {
        $username = 'testuser';
        $password = 'wrongpassword';

        $this->authRepository->method('getUser')->with($username, $password)->willReturn(null);

        $result = $this->authService->login($username, $password);

        $this->assertFalse($result);
    }

    public function testRegisterSuccess()
    {
        $username = 'newuser';
        $password = 'newpassword';

        $this->connection->method('insert')->with('users', ['username' => $username, 'password' => $password])->willReturn(1);

        $result = $this->authService->register($username, $password);

        $this->assertTrue($result);
    }

    public function testRegisterFailure()
    {
        $username = 'newuser';
        $password = 'newpassword';

        $this->connection->method('insert')->with('users', ['username' => $username, 'password' => $password])->willReturn(0);

        $result = $this->authService->register($username, $password);

        $this->assertFalse($result);
    }
}