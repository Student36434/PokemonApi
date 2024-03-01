<?php

declare(strict_types=1);

namespace Vovchenko\PokemonApi\Plugin\Catalog\Model;

use Magento\Catalog\Model\Product as BaseProduct;
use Vovchenko\PokemonApi\Helper\PokemonData;

class Product
{
    /**
     * @param PokemonData $pokemonData
     */
    public function __construct(
        protected PokemonData $pokemonData
    ) {
    }

    /**
     * Modify product name if pokemon data exist
     *
     * @param BaseProduct $subject
     * @param string $result
     * @return string
     */
    public function afterGetName(BaseProduct $subject, string $result): string
    {
        $pokemonName = $this->pokemonData->getPokemonName($subject->getData('pokemon_name'));

        return $pokemonName ?? $result;
    }
}
