<?php

namespace Evoware\OllamaPHP\Models;

/**
 * Represents a Modelfile in the Ollama API.
 * Example modelfile:
 * FROM ollama:7b
 * TEMPLATE """<|im_start|>system
 * {{ .System }}<|im_end|>
 * <|im_start|>user
 * {{ .Prompt }}<|im_end|>
 * <|im_start|>assistant
 * """
 * PARAMETER stop "<|im_start|>"
 * PARAMETER stop "<|im_end|>"
 */
class ModelFile
{
    /**
     * The raw data of the Modelfile.
     */
    protected array $data = [];

    /**
     * The FROM instruction defines the base model to use when creating a
     * model.
     *
     * @var string|null The FROM instruction
     */
    protected ?string $parent;

    /**
     * The PARAMETER instruction defines a parameter that can be set when the
     * model is run.
     * Modelfile format: PARAMETER <parameter> <parametervalue>
     *
     * @var string[] The PARAMETER instructions
     */
    protected ?array $parameters;

    /**
     * TEMPLATE of the full prompt template to be passed into the model. It may
     * include (optionally) a system message, a user's message and the
     * response from the model. Note: syntax may be model specific. Templates
     * use Go template syntax.
     * The TEMPLATE instruction defines the full prompt template to be passed into
     * the model. It may include (optionally) a system message, a user's message and
     * the response from the model. Note: syntax may be model specific. Templates use
     * Go template syntax.
     *
     * Modelfile format: TEMPLATE """{{ if .System }}<|im_start|>system
     * {{ .System }}<|im_end|>
     * {{ end }}{{ if .Prompt }}<|im_start|>user
     * {{ .Prompt }}<|im_end|>
     * {{ end }}{|im_start|}>assistant
     * """
     */
    protected ?string $template;

    /**
     * The SYSTEM instruction specifies the system message to be used in the
     * template, if applicable.
     * Modelfile format: SYSTEM """<system message>"""
     */
    protected ?string $system;

    /**
     * The ADAPTER instruction is an optional instruction that specifies any
     * LoRA adapter that should apply to the base model. The value of this
     * instruction should be an absolute path or a path relative to the
     * Modelfile and the file must be in a GGML file format. The adapter should
     * be tuned from the base model otherwise the behaviour is undefined.
     * Modelfile format: ADAPTER ./ollama-lora.bin
     */
    protected ?string $adapter;

    /**
     * The LICENSE instruction allows you to specify the legal license under
     * which the model used with this Modelfile is shared or distributed.
     * Modelfile format: LICENSE """
     * <license text>
     * """
     */
    protected ?string $license;

    /**
     * The MESSAGE instruction allows you to specify a message history for the
     * model to use when responding. Use multiple iterations of the MESSAGE
     * command to build up a conversation which will guide the model to answer
     * in a similar way.
     * Modelfile format: MESSAGE <role> <message>
     */
    protected ?array $chatHistory = [];

    /**
     * The details of the Modelfile.
     */
    protected ?array $details;

    private const VALID_PARAMETERS = [
        'mirostat' => 'int',
        'mirostatEta' => 'int',
        'mirostatTau' => 'int',
        'numCtx' => 'int',
        'numGqa' => 'int',
        'numGpu' => 'int',
        'numThread' => 'int',
        'repeatLastN' => 'int',
        'repeatPenalty' => 'float',
        'temperature' => 'float',
        'stop' => 'string',
        'seed' => 'int',
        'numPredict' => 'int',
        'topK' => 'int',
        'topP' => 'float',
    ];

    public function __construct(array $data = [])
    {
        $this->data = $data;
        $this->parent = $data['from'] ?? null;
        $this->template = $data['template'] ?? null;
        $this->system = $data['system'] ?? null;
        $this->adapter = $data['adapter'] ?? null;
        $this->license = $data['license'] ?? null;
        $this->chatHistory = $data['messages'] ?? [];
        $this->parameters = array_intersect_key($data['parameters'] ?? [], self::VALID_PARAMETERS);
        $this->normalizeParameters();
        $this->details = $data['details'] ?? [];
    }

    /**
     * Convert ModelFile to array
     */
    public function toArray(): array
    {
        return [
            'parent' => $this->parent,
            'template' => $this->template,
            'system' => $this->system,
            'adapter' => $this->adapter,
            'license' => $this->license,
            'chatHistory' => $this->chatHistory,
            'parameters' => $this->parameters,
            'details' => $this->details,
        ];
    }

    /**
     * Convert ModelFile to JSON
     */
    public function toJson(int $options = 0): string
    {
        return json_encode($this->toArray(), $options);
    }

    /** Getters */
    public function getParent(): string
    {
        return $this->parent;
    }

    public function getTemplate(): string
    {
        return $this->template;
    }

    public function getSystem(): string
    {
        return $this->system;
    }

    public function getAdapter(): string
    {
        return $this->adapter;
    }

    public function getLicense(): string
    {
        return $this->license;
    }

    public function getChatHistory(): array
    {
        return $this->chatHistory;
    }

    public function getParameters(): array
    {
        return $this->parameters;
    }

    public function getParameter(string $key): mixed
    {
        return $this->parameters[$key] ?? null;
    }

    public function getDetails(): array
    {
        return $this->details;
    }

    public function getData(): array
    {
        return $this->data;
    }

    /**
     * Normalize the parameters of the object according to predefined types.
     */
    private function normalizeParameters(): void
    {
        foreach (self::VALID_PARAMETERS as $parameter => $type) {
            if (array_key_exists($parameter, $this->parameters)) {
                settype($this->parameters[$parameter], $type);
            }
        }
    }
}
