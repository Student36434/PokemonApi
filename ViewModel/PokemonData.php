<?php

declare(strict_types=1);

namespace Vovchenko\PokemonApi\ViewModel;

use Magento\Framework\View\Element\Block\ArgumentInterface;
use Vovchenko\PokemonApi\Helper\PokemonData as PokemonDataHelper;

class PokemonData implements ArgumentInterface
{
    /**
     * @param PokemonDataHelper $pokemonDataHelper
     */
    public function __construct(
        protected PokemonDataHelper $pokemonDataHelper
    ) {
    }

    /**
     * Get pokemon name from pokemon data
     *
     * @param string|null $pokemonName
     * @return string|null
     */
    public function getPokemonName(?string $pokemonName): ?string
    {
        return $this->pokemonDataHelper->getPokemonName($pokemonName);
    }

    /**
     * Get pokemon image from pokemon data
     *
     * @param string|null $pokemonName
     * @return string|null
     */
    public function getPokemonImageUrl(?string $pokemonName): ?string
    {
        return $this->pokemonDataHelper->getPokemonImageUrl($pokemonName);
    }

    /**
     * Get pokemon details from pokemon data
     *
     * @param string|null $pokemonName
     * @return array|null
     */
    public function getPokemonDetails(?string $pokemonName): ?array
    {
        return $this->pokemonDataHelper->getPokemonDetails($pokemonName);
    }
}
