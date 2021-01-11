<?php

namespace Spatie\WordPressRay\Spatie\Ray\Payloads;

use Spatie\WordPressRay\Spatie\Ray\ArgumentConverter;

class DecodedJsonPayload extends Payload
{
    protected string $value;

    public function __construct(string $value)
    {
        $this->value = $value;
    }

    public function getType(): string
    {
        return 'custom';
    }

    public function getContent(): array
    {
        $decodedJson = json_decode($this->value, true);

        return [
            'content' => ArgumentConverter::convertToPrimitive($decodedJson),
            'label' => '',
        ];
    }
}
