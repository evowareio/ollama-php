<?php

namespace Evoware\OllamaPHP\Tests;

use PHPUnit\Framework\TestCase;
use Evoware\OllamaPHP\Models\ModelFile;

class ModelFileTest extends TestCase
{
    public function testCreateModelFile()
    {
        $modelFile = new ModelFile(['from' => 'mistral:7b']);
        $this->assertInstanceOf(ModelFile::class, $modelFile);
        $this->assertEquals('mistral:7b', $modelFile->getParent());
    }

    public function testToArray()
    {
        $data = [
            'from' => 'ollama:7b',
            'template' => '__TEMPLATE__',
            'system' => '__SYSTEM__',
            'adapter' => '__ADAPTER__',
            'license' => '__LICENSE__',
            'parameters' => [
                'mirostat' => 1,
                'numCtx' => 2,
            ],
            'messages' => [
                ['role' => 'user', 'message' => 'Hello'],
                ['role' => 'assistant', 'message' => 'Welcome, user!'],
            ],
            'details' => [
                'detail1' => 'value1',
                'detail2' => 'value2',
            ],
        ];

        $modelFile = new ModelFile($data);
        $expectedArray = [
            'parent' => 'ollama:7b',
            'template' => '__TEMPLATE__',
            'system' => '__SYSTEM__',
            'adapter' => '__ADAPTER__',
            'license' => '__LICENSE__',
            'chatHistory' => [
                ['role' => 'user', 'message' => 'Hello'],
                ['role' => 'assistant', 'message' => 'Welcome, user!'],
            ],
            'parameters' => [
                'mirostat' => 1,
                'numCtx' => 2,
            ],
            'details' => [
                'detail1' => 'value1',
                'detail2' => 'value2',
            ],
        ];

        $this->assertEquals($expectedArray, $modelFile->toArray());
    }

    public function testToJson()
    {
        $data = [
            'from' => 'ollama:7b',
            'template' => '__TEMPLATE__',
            'system' => '__SYSTEM__',
            'adapter' => '__ADAPTER__',
            'license' => '__LICENSE__',
            'parameters' => [
                'mirostat' => 1,
                'numCtx' => 2,
            ],
            'details' => [
                'detail1' => 'value1',
                'detail2' => 'value2',
            ],
        ];

        $modelFile = new ModelFile($data);
        $expectedJson = '{"parent":"ollama:7b","template":"__TEMPLATE__","system":"__SYSTEM__","adapter":"__ADAPTER__","license":"__LICENSE__","chatHistory":[],"parameters":{"mirostat":1,"numCtx":2},"details":{"detail1":"value1","detail2":"value2"}}';

        $this->assertEquals($expectedJson, $modelFile->toJson());
    }

    public function testGetters()
    {
        $data = [
            'from' => 'ollama:7b',
            'template' => '__TEMPLATE__',
            'system' => '__SYSTEM__',
            'adapter' => '__ADAPTER__',
            'license' => '__LICENSE__',
            'messages' => [
                ['role' => 'user', 'message' => 'Hello'],
                ['role' => 'assistant', 'message' => 'Welcome, user!'],
            ],
            'parameters' => [
                'mirostat' => 1,
                'numCtx' => 2,
            ],
            'details' => [
                'detail1' => 'value1',
                'detail2' => 'value2',
            ],
        ];

        $modelFile = new ModelFile($data);

        $this->assertEquals('ollama:7b', $modelFile->getParent());
        $this->assertEquals('__TEMPLATE__', $modelFile->getTemplate());
        $this->assertEquals('__SYSTEM__', $modelFile->getSystem());
        $this->assertEquals('__ADAPTER__', $modelFile->getAdapter());
        $this->assertEquals('__LICENSE__', $modelFile->getLicense());
        $this->assertEquals([
            ['role' => 'user', 'message' => 'Hello'],
            ['role' => 'assistant', 'message' => 'Welcome, user!'],
        ], $modelFile->getChatHistory());
        $this->assertEquals(['mirostat' => 1, 'numCtx' => 2], $modelFile->getParameters());
        $this->assertEquals(1, $modelFile->getParameter('mirostat'));
        $this->assertEquals(['detail1' => 'value1', 'detail2' => 'value2'], $modelFile->getDetails());
    }


    public function testModelfileToStringConversion()
    {
        $modelFile = new ModelFile([
            'from' => 'ollama:7b',
            'system' => 'This is a system message',
            'adapter' => '__ADAPTER__',
            'license' => '__LICENSE__',
            'messages' => [
                ['role' => 'user', 'message' => 'Hello'],
                ['role' => 'assistant', 'message' => 'Welcome, user!'],
            ]
        ]);

        $modelfileString = (string) $modelFile;

        $expectedText = <<<EOD
FROM ollama:7b
SYSTEM This is a system message
ADAPTER __ADAPTER__
LICENSE __LICENSE__
MESSAGE user Hello
MESSAGE assistant Welcome, user!
EOD;

        $this->assertEquals($expectedText, $modelfileString);
    }
}
