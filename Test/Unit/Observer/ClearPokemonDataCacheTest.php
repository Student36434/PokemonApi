<?php

declare(strict_types=1);

namespace Vovchenko\PokemonApi\Test\Unit\Observer;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Magento\Framework\Event\Observer;
use Psr\Log\LoggerInterface;
use Magento\Framework\App\CacheInterface;
use Vovchenko\PokemonApi\Observer\ClearPokemonDataCache;
use Magento\Catalog\Model\Product;

class ClearPokemonDataCacheTest extends TestCase
{
    /**
     * @var CacheInterface|MockObject
     */
    protected MockObject|CacheInterface $cacheMock;

    /**
     * @var LoggerInterface|MockObject
     */
    protected LoggerInterface|MockObject $loggerMock;

    /**
     * @var MockObject|Observer
     */
    protected MockObject|Observer $observerMock;

    /**
     * @var MockObject|Product
     */
    protected MockObject|Product $productMock;

    protected function setUp(): void
    {
        $this->cacheMock = $this->getMockBuilder(CacheInterface::class)
            ->getMock();

        $this->loggerMock = $this->getMockBuilder(LoggerInterface::class)
            ->getMock();

        $this->observerMock = $this->getMockBuilder(Observer::class)
            ->disableOriginalConstructor()
            ->addMethods(['getProduct'])
            ->getMock();

        $this->productMock = $this->getMockBuilder(Product::class)
            ->disableOriginalConstructor()
            ->getMock();
    }

    public function testExecuteCacheRemoval()
    {
        $pokemonName = 'pikachu';
        $cacheKey = 'pokemonapi_pokemon_data_pikachu';

        $this->productMock->expects($this->once())
            ->method('getOrigData')
            ->with('pokemon_name')
            ->willReturn($pokemonName);

        $this->observerMock->expects($this->once())
            ->method('getProduct')
            ->willReturn($this->productMock);

        $this->cacheMock->expects($this->once())
            ->method('remove')
            ->with($cacheKey);

        $cacheClearer = new ClearPokemonDataCache(
            $this->cacheMock,
            $this->loggerMock
        );

        $cacheClearer->execute($this->observerMock);
    }

    public function testExecuteNoCacheRemoval()
    {
        $this->productMock->expects($this->once())
            ->method('getOrigData')
            ->with('pokemon_name')
            ->willReturn(null);

        $this->observerMock->expects($this->once())
            ->method('getProduct')
            ->willReturn($this->productMock);

        $this->cacheMock->expects($this->never())
            ->method('remove');

        $cacheClearer = new ClearPokemonDataCache(
            $this->cacheMock,
            $this->loggerMock
        );

        $cacheClearer->execute($this->observerMock);
    }
}
