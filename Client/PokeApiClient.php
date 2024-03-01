<?php

declare(strict_types=1);

namespace Vovchenko\PokemonApi\Client;

use Exception;
use Magento\Framework\HTTP\Client\Curl;
use Magento\Framework\HTTP\ClientFactory;
use Magento\Framework\Serialize\SerializerInterface;
use Psr\Log\LoggerInterface;
use Vovchenko\PokemonApi\Exception\PokeApiException;
use Vovchenko\PokemonApi\Helper\ConfigData;

class PokeApiClient
{
    /**
     * @param ClientFactory $httpClientFactory
     * @param ConfigData $configHelper
     * @param SerializerInterface $serializer
     * @param LoggerInterface $logger
     */
    public function __construct(
        protected ClientFactory $httpClientFactory,
        protected ConfigData $configHelper,
        protected SerializerInterface $serializer,
        protected LoggerInterface $logger
    ) {
    }

    /**
     * Fetch pokemon data by pokemon name
     *
     * @param string $pokemonName
     * @return null|array
     */
    public function fetchPokemonData(string $pokemonName): ?array
    {
        try {
            $url = $this->configHelper->getBaseUrl() . $this->configHelper->getResourceName() . urlencode($pokemonName);
            $httpClient = $this->httpClientFactory->create();
            $httpClient->get($url);

            $this->validateResponse($httpClient);

            $responseBody = $httpClient->getBody();
            $pokemonData = $this->serializer->unserialize($responseBody);

            $this->validatePokemonData($pokemonData);

            return $pokemonData;
        } catch (PokeApiException $e) {
            $this->logger->warning('Fetch data for pokemon_name:' . ' ' . $pokemonName . ' ' . $e->getMessage());
            return null;
        } catch (Exception $e) {
            $this->logger->critical('Fetch data for pokemon_name:' . ' ' . $pokemonName . ' ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Validate response
     *
     * @param  Curl $response
     * @return void
     * @throws PokeApiException
     */
    private function validateResponse(Curl $response): void
    {
        $responseStatus = $response->getStatus();

        if ($responseStatus !== 200) {
            throw new PokeApiException(
                'Response status:' . ' ' . $responseStatus . ' ' . 'response message:' . ' ' . $response->getBody()
            );
        }
    }

    /**
     * Validate Pokemon Data
     *
     * @param array $pokemonData
     * @return void
     * @throws PokeApiException
     */
    private function validatePokemonData(array $pokemonData): void
    {
        if (!isset($pokemonData['name'])) {
            throw new PokeApiException('Wrong pokemon data, key name does not exist');
        }

        if (!isset($pokemonData['sprites']['other']['official-artwork']['front_default'])) {
            throw new PokeApiException('Wrong pokemon data, key image does not exist');
        }
    }
}
