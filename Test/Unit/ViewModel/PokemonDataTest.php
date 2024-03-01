<?php

declare(strict_types=1);

namespace Vovchenko\PokemonApi\Test\Unit\ViewModel;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Vovchenko\PokemonApi\Helper\PokemonData as PokemonDataHelper;
use Vovchenko\PokemonApi\ViewModel\PokemonData;

class PokemonDataTest extends TestCase
{
    /**
     * @var MockObject|PokemonDataHelper
     */
    protected PokemonDataHelper|MockObject $pokemonDataHelperMock;

    /**
     * @var PokemonData
     */
    protected PokemonData $pokemonDataViewModel;

    protected function setUp(): void
    {
        $this->pokemonDataHelperMock = $this->createMock(PokemonDataHelper::class);
        $this->pokemonDataViewModel = new PokemonData($this->pokemonDataHelperMock);
    }

    public function testGetPokemonName(): void
    {
        $pokemonName = 'Pikachu';
        $expectedPokemonName = 'Pikachu';
        $this->pokemonDataHelperMock->expects($this->once())
            ->method('getPokemonName')
            ->with($pokemonName)
            ->willReturn($expectedPokemonName);

        $result = $this->pokemonDataViewModel->getPokemonName($pokemonName);

        $this->assertSame($expectedPokemonName, $result);
    }

    public function testGetPokemonImageUrl(): void
    {
        $pokemonName = 'Pikachu';
        $expectedImageUrl = 'https://example.com/pikachu.png';
        $this->pokemonDataHelperMock->expects($this->once())
            ->method('getPokemonImageUrl')
            ->with($pokemonName)
            ->willReturn($expectedImageUrl);

        $result = $this->pokemonDataViewModel->getPokemonImageUrl($pokemonName);

        $this->assertSame($expectedImageUrl, $result);
    }

    public function testGetPokemonDetails(): void
    {
        $pokemonName = 'Pikachu';
        $expectedPokemonDetails = [
            'name' => 'Pikachu',
            'height' => 40,
            'weight' => 6
        ];
        $this->pokemonDataHelperMock->expects($this->once())
            ->method('getPokemonDetails')
            ->with($pokemonName)
            ->willReturn($expectedPokemonDetails);

        $result = $this->pokemonDataViewModel->getPokemonDetails($pokemonName);

        $this->assertSame($expectedPokemonDetails, $result);
    }
}
