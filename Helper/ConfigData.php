<?php

declare(strict_types=1);

namespace Vovchenko\PokemonApi\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Store\Model\ScopeInterface;

class ConfigData extends AbstractHelper
{
    private const XML_PATH_BASE_URL = 'pokemon_api/general/base_url';
    private const XML_PATH_RESOURCE_NAME = 'pokemon_api/general/resource_name';

    /**
     * Get base url for pokemon api from configuration
     *
     * @param int|null $storeId
     * @return string
     */
    public function getBaseUrl(?int $storeId = null): string
    {
        return $this->scopeConfig->getValue(self::XML_PATH_BASE_URL, ScopeInterface::SCOPE_STORE, $storeId);
    }

    /**
     * Get resource name api for pokemon from configuration
     *
     * @param int|null $storeId
     * @return string
     */
    public function getResourceName(?int $storeId = null): string
    {
        return $this->scopeConfig->getValue(self::XML_PATH_RESOURCE_NAME, ScopeInterface::SCOPE_STORE, $storeId);
    }
}
