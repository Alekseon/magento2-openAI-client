<?php
/**
 * Copyright © Alekseon sp. z o.o.
 * http://www.alekseon.com/
 */
namespace Alekseon\OpenAIApiClient\Model;

use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Store\Model\StoreManagerInterface;
use OpenAI;
use OpenAI\Client;
use Alekseon\OpenAIApiClient\Model\Response\Completions;
use \Exception;

class OpenAIClient
{
    /**
     * @var Client
     */
    protected Client $openAIClient;
    /**
     * @var string
     */
    protected string $model = 'text-davinci-003';

    /**
     * @param Config $config
     * @param StoreManagerInterface $storeManager
     * @throws NoSuchEntityException
     */
    public function __construct(
        protected readonly Config $config,
        protected readonly StoreManagerInterface $storeManager
    ) { }

    /**
     * @return void
     * @throws \Exception
     */
    public function getOpenAIClient(): Client
    {
        try {
            $apiKey = $this->config->getApiKey($this->storeManager->getStore()->getId());
            $this->openAIClient = OpenAI::client($apiKey);
        } catch (\Exception $e) {
            throw new Exception('Invalid api key. Please change it in Configurations. ' . $e->getMessage());
        }

        return $this->openAIClient;
    }

    /**
     * @param string $question
     * @return Completions
     */
    public function getCompletions(string $question): Completions
    {
        $question = strip_tags($question);
        $question = trim(preg_replace('/\s\s+/', ' ', $question));

        $response = $this->getOpenAIClient()->completions()->create([
            'model' => $this->model,
            'prompt' => $question,
            'max_tokens' => 120,
            //'temperature' => 0
        ]);

        return Completions::from($response);
    }

    /**
     * @param string $model
     * @return void
     */
    public function setModel(string $model): void
    {
        $this->model = $model;
    }
}
