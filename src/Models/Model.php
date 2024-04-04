<?php

namespace Evoware\OllamaPHP\DataObjects;

use DateTime;

class Model
{
    /** @var string Model name */
    public string $name;

    /** @var string the model ID */
    public string $id;

    /** @var int Size in megabytes */
    public int $size;

    /** @var string Model version */
    public string $version;

    /** @var string Model digest */
    public string $digest;

    /** @var string Model version */
    public array $details;

    public DateTime $modifiedAt;

    public function __construct(array $data)
    {
        $this->name = $data['name'];
        $this->id = $data['model'];
        $this->size = $data['size'];
        $this->digest = $data['digest'];
        $this->modifiedAt = DateTime::createFromFormat(DateTime::ATOM, $data['modified_at']);
        $this->details = $data['details'];
    }

    public function __toString(): string
    {
        return $this->name;
    }
}
