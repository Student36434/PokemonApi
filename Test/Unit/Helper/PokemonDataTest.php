<?php

declare(strict_types=1);

namespace Vovchenko\PokemonApi\Test\Unit\Helper;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\App\Helper\Context;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Cache\ConfigInterface;
use Magento\Framework\Event\ManagerInterface;
use Magento\Framework\HTTP\Header;
use Magento\Framework\HTTP\PhpEnvironment\RemoteAddress;
use Magento\Framework\Module\Manager;
use Magento\Framework\Url\DecoderInterface;
use Magento\Framework\Url\EncoderInterface;
use Magento\Framework\UrlInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Magento\Framework\App\CacheInterface;
use Magento\Framework\Serialize\SerializerInterface;
use Psr\Log\LoggerInterface;
use Vovchenko\PokemonApi\Client\PokeApiClient;
use Vovchenko\PokemonApi\Helper\PokemonData;

/**
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class PokemonDataTest extends TestCase
{
    /**
     * @var MockObject|PokeApiClient
     */
    protected MockObject|PokeApiClient $apiClientMock;

    /**
     * @var CacheInterface|MockObject
     */
    protected MockObject|CacheInterface $cacheMock;

    /**
     * @var MockObject|SerializerInterface
     */
    protected SerializerInterface|MockObject $serializerMock;

    /**
     * @var Context|MockObject
     */
    protected Context|MockObject $contextMock;

    protected function setUp(): void
    {
        $this->apiClientMock = $this->getMockBuilder(PokeApiClient::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->cacheMock = $this->getMockBuilder(CacheInterface::class)
            ->getMock();

        $this->serializerMock = $this->getMockBuilder(SerializerInterface::class)
            ->getMock();

        $this->contextMock = new Context(
            $this->getMockForAbstractClass(EncoderInterface::class),
            $this->getMockForAbstractClass(DecoderInterface::class),
            $this->getMockForAbstractClass(LoggerInterface::class),
            $this->createMock(Manager::class),
            $this->getMockForAbstractClass(RequestInterface::class),
            $this->getMockForAbstractClass(ConfigInterface::class),
            $this->getMockForAbstractClass(ManagerInterface::class),
            $this->getMockForAbstractClass(UrlInterface::class),
            $this->createMock(Header::class),
            $this->createMock(RemoteAddress::class),
            $this->getMockForAbstractClass(ScopeConfigInterface::class),
        );
    }

    public function testGetPokemonName()
    {
        $pokemonName = 'pikachu';
        $expectedPokemonName = 'Pikachu';

        $pokemonData = ['name' => $expectedPokemonName];

        $this->apiClientMock->method('fetchPokemonData')->willReturn($pokemonData);

        $pokemonDataHelper = new PokemonData(
            $this->contextMock,
            $this->apiClientMock,
            $this->cacheMock,
            $this->serializerMock
        );

        $actualPokemonName = $pokemonDataHelper->getPokemonName($pokemonName);

        $this->assertEquals($expectedPokemonName, $actualPokemonName);
    }

    public function testGetPokemonImageUrl()
    {
        $pokemonName = 'pikachu';
        $expectedImageUrl = 'https://example.com/pikachu.jpg';

        $pokemonData = [
            'sprites' => [
                'other' => [
                    'official-artwork' => [
                        'front_default' => $expectedImageUrl
                    ]
                ]
            ]
        ];

        $this->apiClientMock->method('fetchPokemonData')->willReturn($pokemonData);

        $pokemonDataHelper = new PokemonData(
            $this->contextMock,
            $this->apiClientMock,
            $this->cacheMock,
            $this->serializerMock
        );

        $actualImageUrl = $pokemonDataHelper->getPokemonImageUrl($pokemonName);

        $this->assertEquals($expectedImageUrl, $actualImageUrl);
    }
}
