<?php

namespace Evoware\OllamaPHP\DataObjects;

class Model
{
    public string $name;

    /** @var string the model ID */
    public string $id;

    /** @var int Size in megabytes */
    public int $size;

    public string $version;
}