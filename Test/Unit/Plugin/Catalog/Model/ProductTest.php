<?php

declare(strict_types=1);

namespace Vovchenko\PokemonApi\Test\Unit\Plugin\Catalog\Model;

use Magento\Catalog\Model\Product as BaseProduct;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Vovchenko\PokemonApi\Helper\PokemonData;
use Vovchenko\PokemonApi\Plugin\Catalog\Model\Product;

class ProductTest extends TestCase
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

    public function testAfterGetNameWithPokemonName()
    {
        $baseProductMock = $this->getMockBuilder(BaseProduct::class)
            ->disableOriginalConstructor()
            ->getMock();

        $baseProductMock->expects($this->once())
            ->method('getData')
            ->with('pokemon_name')
            ->willReturn('pikachu');

        $this->pokemonDataMock->expects($this->once())
            ->method('getPokemonName')
            ->with('pikachu')
            ->willReturn('Pikachu');

        $plugin = new Product($this->pokemonDataMock);
        $result = $plugin->afterGetName($baseProductMock, 'Original Product Name');

        $this->assertEquals('Pikachu', $result);
    }

    public function testAfterGetNameWithoutPokemonName()
    {
        $baseProductMock = $this->getMockBuilder(BaseProduct::class)
            ->disableOriginalConstructor()
            ->getMock();

        $baseProductMock->expects($this->once())
            ->method('getData')
            ->with('pokemon_name')
            ->willReturn(null);

        $plugin = new Product($this->pokemonDataMock);
        $result = $plugin->afterGetName($baseProductMock, 'Original Product Name');

        $this->assertEquals('Original Product Name', $result);
    }
}
