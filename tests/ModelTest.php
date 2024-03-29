<?php

use PHPUnit\Framework\TestCase;
use Evoware\OllamaPHP\OllamaClient;
use Evoware\OllamaPHP\DataObjects\Model;

class ModelTest extends TestCase {
    private $client;
    private $model;

    protected function setUp(): void {
        $this->client = $this->createMock(OllamaClient::class);
        $this->model = new Model($this->client);
    }

    public function testListModels() {
        $this->client->method('request')
                     ->willReturn(['models' => ['model1', 'model2']]);

        $models = $this->model->list();
        $this->assertCount(2, $models);
        $this->assertEquals(['model1', 'model2'], $models);
    }

    public function testAddModel() {
        $this->client->expects($this->once())
                     ->method('request')
                     ->with($this->equalTo('POST'), $this->equalTo('/api/models'), $this->anything())
                     ->willReturn(['success' => true]);

        $result = $this->model->add('newModel');
        $this->assertTrue($result);
    }

    public function testDeleteModel() {
        $this->client->expects($this->once())
                     ->method('request')
                     ->with($this->equalTo('DELETE'), $this->equalTo('/api/models/newModel'))
                     ->willReturn(['success' => true]);

        $result = $this->model->delete('newModel');
        $this->assertTrue($result);
    }

    // Additional tests as required
}
