<?php

declare(strict_types=1);

namespace Vovchenko\PokemonApi\Observer;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Exception;
use Psr\Log\LoggerInterface;
use Vovchenko\PokemonApi\Helper\PokemonData;
use Magento\Framework\App\CacheInterface;

class ClearPokemonDataCache implements ObserverInterface
{
    /**
     * @param CacheInterface $cache
     * @param LoggerInterface $logger
     */
    public function __construct(
        protected CacheInterface $cache,
        protected LoggerInterface $logger
    ) {
    }

    /**
     * Clear pokemon data from cache before save product
     *
     * @param Observer $observer
     * @return void
     */
    public function execute(Observer $observer): void
    {
        try {
            $product = $observer->getProduct();
            $pokemonName = $product->getOrigData('pokemon_name');

            if ($pokemonName) {
                $cacheKey = PokemonData::CACHE_KEY_PREFIX . $pokemonName;
                $this->cache->remove($cacheKey);
            }
        } catch (Exception $e) {
            $this->logger->critical(
                'Clear pokemon data cache for product:' . ' ' . $product->getName() . ' ' . $e->getMessage()
            );
        }
    }
}
