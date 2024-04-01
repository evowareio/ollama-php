<?php

namespace Evoweb\OllamaPHP\Tests;

use PHPUnit\Framework\TestCase;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\Client;
use Evoware\OllamaPHP\Repositories\ModelRepository;
use Evoware\OllamaPHP\Models\ModelFile;
use Evoware\OllamaPHP\Exceptions\OllamaException;
use Evoware\OllamaPHP\DataObjects\Model;

class ModelRepositoryTest extends TestCase
{
    /**
     * @var ModelRepository
     */
    private $repository;

    protected function setUp(): void
    {
        $mock = new MockHandler([
            new Response(200, ['content-type' => 'application/json', 'charset' => 'utf-8'], 'Hello, World'),
            new Response(200, ['Content-Length' => 0]),
            new Response(200, ['Content-Length' => 0]),
            new Response(200, ['Content-Length' => 0]),
            new Response(200, ['Content-Length' => 0]),
            new Response(200, ['Content-Length' => 0]),
            new Response(200, ['Content-Length' => 0]),
        ]);

        $this->repository = new ModelRepository(new Client(['handler' => $mock]));
    }

    public function testList()
    {
        $models = $this->repository->list();

        $this->assertCount(1, $models);
        $this->assertInstanceOf(Model::class, $models[0]);
        $this->assertEquals('testmodel:latest', $models[0]->getName());
    }

    public function testInfo()
    {
        // TODO implement this
        $response = $this->repository->info('testmodel:latest');
        
        $this->assertInstanceOf(Model::class, $response);
        $this->assertEquals('testmodel:latest', $response->getName());
        $this->assertEquals(4108928574, $response->getSize());
        $this->assertEquals('95477a2659b7539758230498d6ea9f6bfa5aa51ffb3dea9f37c91cacbac459c1', $response->getDigest());
        $this->assertEquals('Q4_0', $response->getDetails()['quantization_level']);
    }

    public function testCreate()
    {
        // TODO implement this
    }

    public function testCopy()
    {
        // TODO implement this
    }

    public function testDelete()
    {
        // TODO implement this
    }

    public function testPull()
    {
        $this->assertTrue($this->repository->pull('testmodel:latest'));
    }

    public function testPush()
    {
        $this->assertTrue($this->repository->push('testmodel:latest'));
    }
}
