<?php

declare(strict_types=1);

namespace Vovchenko\PokemonApi\Helper;

use Exception;
use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;
use Magento\Framework\App\CacheInterface;
use Magento\Framework\Serialize\SerializerInterface;
use Vovchenko\PokemonApi\Client\PokeApiClient;

class PokemonData extends AbstractHelper
{
    public const CACHE_KEY_PREFIX = 'pokemonapi_pokemon_data_';

    /**
     * @param Context $context
     * @param PokeApiClient $apiClient
     * @param CacheInterface $cache
     * @param SerializerInterface $serializer
     */
    public function __construct(
        Context $context,
        protected PokeApiClient $apiClient,
        protected CacheInterface $cache,
        protected SerializerInterface $serializer
    ) {
        parent::__construct($context);
    }

    /**
     * Get pokemon name from pokemon data
     *
     * @param string|null $pokemonName
     * @return string|null
     */
    public function getPokemonName(?string $pokemonName): ?string
    {
        $pokemonData = $this->getPokemonData($pokemonName);

        return $pokemonData['name'] ?? null;
    }

    /**
     * Get pokemon image from pokemon data
     *
     * @param string|null $pokemonName
     * @return string|null
     */
    public function getPokemonImageUrl(?string $pokemonName): ?string
    {
        $pokemonData = $this->getPokemonData($pokemonName);

        return $pokemonData['sprites']['other']['official-artwork']['front_default'] ?? null;
    }

    /**
     * Get pokemon details from pokemon data
     *
     * @param string|null $pokemonName
     * @return array|null
     */
    public function getPokemonDetails(?string $pokemonName): ?array
    {
        try {
            $pokemonDetails = [];
            $pokemonData = $this->getPokemonData($pokemonName);

            $pokemonDetails['name'] = $pokemonData['name'];
            $pokemonDetails['height'] = $pokemonData['height'];
            $pokemonDetails['weight'] = $pokemonData['weight'];
            $pokemonDetails['id'] = $pokemonData['id'];
            $pokemonDetails['order'] = $pokemonData['order'];
            $pokemonDetails['stats'] = $this->mapStats($pokemonData['stats']);
            $pokemonDetails['types'] = $this->mapTypes($pokemonData['types']);

            return $pokemonDetails;
        } catch (Exception $e) {
            $this->_logger->warning(
                'Get pokemon details for pokemon_name:' . ' ' . $pokemonName . ' ' . $e->getMessage()
            );
            return null;
        }
    }

    /**
     * Get pokemon data from API and set to cache
     *
     * @param string|null $pokemonName
     * @return mixed
     */
    public function getPokemonData(?string $pokemonName): mixed
    {
        try {
            if (!$pokemonName) {
                return null;
            }

            $cacheKey = self::CACHE_KEY_PREFIX . $pokemonName;
            $cachedData = $this->cache->load($cacheKey);

            if ($cachedData) {
                return $this->serializer->unserialize($cachedData);
            }

            $pokemonData = $this->apiClient->fetchPokemonData($pokemonName);
            $this->cache->save($this->serializer->serialize($pokemonData), $cacheKey);

            return $pokemonData;
        } catch (Exception $e) {
            $this->_logger->warning('Get data for pokemon_name:' . ' ' . $pokemonName . ' ' . $e->getMessage());

            return null;
        }
    }

    /**
     * Map stats
     *
     * @param array|null $stats
     * @return string
     */
    private function mapStats(?array $stats): string
    {
        $mapStats = '';
        foreach ($stats as $stat) {
            $mapStats .= sprintf("%s: %s<br>", $stat['stat']['name'], $stat['base_stat']);
        }

        return $mapStats;
    }

    /**
     * Map Types
     *
     * @param array|null $types
     * @return string
     */
    private function mapTypes(?array $types): string
    {
        $mapTypes = '';
        foreach ($types as $type) {
            $mapTypes .= sprintf("%s<br>", $type['type']['name']);
        }

        return $mapTypes;
    }
}
