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
    protected readonly Client $openAIClient;
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
    ) {
        $this->initializeOpenAiClient();
    }

    /**
     * @return void
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function initializeOpenAiClient(): void
    {
        try {
            $apiKey = $this->config->getApiKey($this->storeManager->getStore()->getId());
            $this->openAIClient = OpenAI::client($apiKey);
        } catch (\Exception $e) {
            throw new Exception('Invalid api key. Please change it in Configurations. ' . $e->getMessage());
        }
    }

    /**
     * @param string $question
     * @return Completions
     */
    public function getCompletions(string $question): Completions
    {
        $response = $this->openAIClient->completions()->create([
            'model' => $this->model,
            'prompt' => $question,
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
