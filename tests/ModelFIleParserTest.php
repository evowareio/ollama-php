<?php

namespace Evoware\OllamaPHP\Tests;

use Evoware\OllamaPHP\Parsers\ModelFileParser;
use PHPUnit\Framework\TestCase;

class ModelFileParserTest extends TestCase
{
    public function testParseSingleLineInstructions()
    {
        $parser = new ModelFileParser();
        $content = "FROM llama2:7b\nPARAMETER temperature 0.7";

        $expected = [
            'from' => 'llama2:7b',
            'parameters' => [
                'temperature' => '0.7',
            ],
        ];

        $this->assertEquals($expected, $parser->parse($content));
    }

    public function testParseMultilineInstructions()
    {
        $parser = new ModelFileParser();
        $content = <<<'EOD'
TEMPLATE """
[INST] {{ if .System }}<<SYS>>{{ .System }}<</SYS>>

{{ end }}{{ .Prompt }} [/INST] """
EOD;

        $expected = [
            'template' => "[INST] {{ if .System }}<<SYS>>{{ .System }}<</SYS>>\n\n{{ end }}{{ .Prompt }} [/INST] ",
        ];

        $this->assertEquals($expected, $parser->parse($content));
    }

    public function testParseMixedInstructions()
    {
        $parser = new ModelFileParser();
        $content = <<<'EOD'
FROM llama2:7b
PARAMETER temperature 0.7
TEMPLATE """
Hello, {{ .Name }}. Welcome to our platform.
"""
MESSAGE user Hello
MESSAGE assistant Welcome, user!
EOD;

        $expected = [
            'from' => 'llama2:7b',
            'template' => 'Hello, {{ .Name }}. Welcome to our platform.',
            'parameters' => ['temperature' => '0.7'],
            'messages' => [
                ['role' => 'user', 'message' => 'Hello'],
                ['role' => 'assistant', 'message' => 'Welcome, user!'],
            ],
        ];

        $this->assertEquals($expected, $parser->parse($content));
    }
}
