<?php

declare(strict_types=1);

namespace Vovchenko\PokemonApi\Test\Unit\Plugin\Block\Product;

use Magento\Catalog\Block\Product\Image;
use Magento\Catalog\Block\Product\ImageFactory;
use Magento\Catalog\Model\Product;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Vovchenko\PokemonApi\Helper\PokemonData;
use Vovchenko\PokemonApi\Plugin\Block\Product\ProductImage;

class ProductImageTest extends TestCase
{
    /**
     * @var PokemonData|MockObject
     */
    protected PokemonData|MockObject $pokemonDataMock;

    protected function setUp(): void
    {
        $this->pokemonDataMock = $this->getMockBuilder(PokemonData::class)
            ->disableOriginalConstructor()
            ->getMock();
    }

    public function testAfterCreateWithPokemonImageUrl()
    {
        $imageFactoryMock = $this->getMockBuilder(ImageFactory::class)
            ->disableOriginalConstructor()
            ->getMock();

        $productMock = $this->getMockBuilder(Product::class)
            ->disableOriginalConstructor()
            ->getMock();

        $resultImageMock = $this->getMockBuilder(Image::class)
            ->disableOriginalConstructor()
            ->getMock();

        $productMock->expects($this->once())
            ->method('getData')
            ->with('pokemon_name')
            ->willReturn('pikachu');

        $this->pokemonDataMock->expects($this->once())
            ->method('getPokemonImageUrl')
            ->with('pikachu')
            ->willReturn('https://example.com/pikachu.png');

        $resultImageMock->expects($this->once())
            ->method('setData')
            ->with('image_url', 'https://example.com/pikachu.png')
            ->willReturnSelf();

        $plugin = new ProductImage($this->pokemonDataMock);
        $result = $plugin->afterCreate($imageFactoryMock, $resultImageMock, $productMock, 'image_id');

        $this->assertSame($resultImageMock, $result);
    }
}
