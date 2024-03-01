<?php

declare(strict_types=1);

namespace Vovchenko\PokemonApi\Plugin\Block\Product;

use Magento\Catalog\Block\Product\Image;
use Magento\Catalog\Block\Product\ImageFactory;
use Magento\Catalog\Model\Product;
use Vovchenko\PokemonApi\Helper\PokemonData;

class ProductImage
{
    /**
     * @param PokemonData $pokemonData
     */
    public function __construct(
        protected PokemonData $pokemonData
    ) {
    }

    /**
     * Modify product image url if pokemon data exist
     *
     * @param ImageFactory $subject
     * @param Image $result
     * @param Product $product
     * @param string $imageId
     * @param array|null $attributes
     * @return Image
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function afterCreate(
        ImageFactory $subject,
        Image $result,
        Product $product,
        string $imageId,
        array $attributes = null
    ): Image {
        $pokemonImageUrl = $this->pokemonData->getPokemonImageUrl($product->getData('pokemon_name'));

        if ($pokemonImageUrl) {
            $result->setData('image_url', $pokemonImageUrl);
        }

        return $result;
    }
}
