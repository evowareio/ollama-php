<?php

namespace Evoware\OllamaPHP\Parsers;

class ModelFileParser
{
    public function parse(string $modelfileContent): array
    {
        $structuredData = [];
        $pattern = '/^(\w+)\s+("""\n?((?:.|\n)*?)\n?"""|(.+))/m';

        preg_match_all($pattern, $modelfileContent, $matches, PREG_SET_ORDER);

        foreach ($matches as $match) {
            $instruction = strtolower($match[1]);
            $value = $match[3] !== '' ? $match[3] : $match[4];
            $value = str_replace(["\r\n", "\r"], "\n", $value);

            switch ($instruction) {
                case 'parameter':
                    // Convert 'parameter' to 'parameters' and handle it accordingly
                    $this->handleParameter('parameters', $value, $structuredData);
                    break;
                case 'message':
                    // Convert 'message' to 'messages' and handle it accordingly
                    $this->handleMessage('messages', $value, $structuredData);
                    break;
                case 'detail':
                    // Handle 'details' instruction
                    $this->handleDetail('details', $value, $structuredData);
                    break;
                default:
                    // Direct assignment for single-value instructions
                    $this->handleOtherInstruction($instruction, $value, $structuredData); 
                    break;
            }
        }

        return $structuredData;
    }

    private function handleParameter(string $instruction, string $value, array &$structuredData)
    {
        list($paramName, $paramValue) = explode(' ', $value, 2);
        if (!isset($structuredData[$instruction])) {
            $structuredData[$instruction] = [];
        }
        $structuredData[$instruction][$paramName] = $paramValue;
    }

    private function handleMessage(string $instruction, string $value, array &$structuredData)
    {
        list($role, $message) = explode(' ', $value, 2);
        if (!isset($structuredData[$instruction])) {
            $structuredData[$instruction] = [];
        }
        $structuredData[$instruction][] = ['role' => $role, 'message' => $message];
    }

    private function handleDetail(string $instruction, string $value, array &$structuredData)
    {
        $structuredData[$instruction][] = json_decode($value, true);
    }

    private function handleOtherInstruction(string $instruction, string $value, array &$structuredData)
    {
        // For instructions expected to have a single occurrence, directly assign the value
        if (in_array($instruction, ['from', 'template', 'license', 'adapter'])) {
            $structuredData[$instruction] = $value;
        } else {
            // Append to an array for other instructions that might have multiple values
            if (!isset($structuredData[$instruction])) {
                $structuredData[$instruction] = [];
            }
            $structuredData[$instruction][] = $value;
        }
    }
}
