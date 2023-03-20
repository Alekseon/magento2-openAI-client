<?php
/**
 * Copyright © Alekseon sp. z o.o.
 * http://www.alekseon.com/
 */
namespace Alekseon\OpenAIApiClient\Model\Response;

use InvalidArgumentException;
use OpenAI\Responses\Completions\CreateResponse;
use OpenAI\Responses\Completions\CreateResponseUsage;

class Completions
{
    /**
     * @param string $id
     * @param string $object
     * @param int $created
     * @param string $model
     * @param array $choices
     * @param CreateResponseUsage $usage
     */
    private function __construct(
        public readonly string $id,
        public readonly string $object,
        public readonly int $created,
        public readonly string $model,
        public readonly array $choices,
        public readonly CreateResponseUsage $usage,
    ) {
    }

    /**
     * @param CreateResponse $createResponse
     * @return static
     */
    public static function from(CreateResponse $createResponse): self
    {
        $attributes = $createResponse->toArray();

        try {
            return new self(
                $attributes['id'],
                $attributes['object'],
                $attributes['created'],
                $attributes['model'],
                $attributes['choices'],
                CreateResponseUsage::from($attributes['usage'])
            );
        } catch (\Exception $e) {
            throw new InvalidArgumentException('Invalid response from OpenAI ' . $e->getMessage());
        }
    }

    /**
     * @return string|false
     */
    public function getChoiceText(): string|false
    {
        $choicetext = ($this->choices[0] && $this->choices[0]['text'])? $this->choices[0]['text'] : '';
        return trim( $choicetext);
    }
}
