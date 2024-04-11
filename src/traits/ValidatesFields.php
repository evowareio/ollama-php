<?php

namespace Evoware\OllamaPHP\Traits;

use Evoware\OllamaPHP\Exceptions\OllamaClientException;

trait ValidatesFields
{
    protected function validate(array $fields, array $data): void
    {
        foreach ($fields as $field) {
            if (!array_key_exists($field, $data) || empty($data[$field])) {
                throw new OllamaClientException("Missing required parameter: $field");
            }
        }
    }
}
