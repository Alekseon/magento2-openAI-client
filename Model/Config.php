<?php
/**
 * Copyright © Alekseon sp. z o.o.
 * http://www.alekseon.com/
 */
namespace Alekseon\OpenAIApiClient\Model;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\Encryption\EncryptorInterface;
use Magento\Store\Model\ScopeInterface;

class Config
{
    final public const ALEKSEON_OPENAIAPICLIENT_API_KEY = 'chat_gpt/general/api_key';

    /**
     * @param ScopeConfigInterface $scopeConfig
     * @param EncryptorInterface $encryptor
     */
    public function __construct(
        protected readonly ScopeConfigInterface $scopeConfig,
        protected readonly EncryptorInterface $encryptor
    ){}

    /**
     * @param string|null $storeId
     * @return string
     */
    public function getApiKey(?string $storeId): string
    {
        $apiKey = $this->scopeConfig->getValue(
            self::ALEKSEON_OPENAIAPICLIENT_API_KEY, ScopeInterface::SCOPE_STORE,$storeId
        );

        return $this->encryptor->decrypt($apiKey);
    }
}
