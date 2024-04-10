<?php

namespace Evoware\OllamaPHP\DataObjects;

use DateTime;

class Model
{
    /** @var string Model name */
    protected string $name;

    /** @var string the model ID */
    protected string $id;

    /** @var int Size in megabytes */
    protected int $size;

    /** @var string Model version */
    protected string $version;

    /** @var string Model digest */
    protected string $digest;

    /** @var string Model version */
    protected array $details;

    /** @var DateTime */
    protected DateTime $modifiedAt;

    public function __construct(array $data)
    {
        // validate all fields in minimalist way
        foreach (['name', 'model', 'size', 'digest', 'modified_at', 'details'] as $key) {
            if (!isset($data[$key]) || empty($data[$key])) {
                throw new \Exception('Missing required field: ' . $key);
            }
        }

        $this->setName($data['name']);
        $this->setId($data['model']);
        $this->setSize($data['size']);
        $this->setDigest($data['digest']);
        $this->modifiedAt = DateTime::createFromFormat('Y-m-d\TH:i:sP', $data['modified_at'] ?? DateTime::createFromFormat(DateTime::ATOM, $data['modified_at']));
        $this->setDetails($data['details']);
    }

    public function __toString(): string
    {
        return $this->getName();
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function setId(string $id): void
    {
        $this->id = $id;
    }

    public function getSize(): int
    {
        return $this->size;
    }

    public function setSize(int $size): void
    {
        $this->size = $size;
    }

    public function getVersion(): string
    {
        return $this->version;
    }

    public function setVersion(string $version): void
    {
        $this->version = $version;
    }

    public function getDigest(): string
    {
        return $this->digest;
    }

    public function setDigest(string $digest): void
    {
        $this->digest = $digest;
    }

    public function getDetails(): array
    {
        return $this->details;
    }

    public function setDetails(array $details): void
    {
        $this->details = $details;
    }

    public function getModifiedAt(): DateTime
    {
        return $this->modifiedAt;
    }
}
