<?php

declare(strict_types=1);

namespace Vovchenko\PokemonApi\Test\Unit\Client;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Magento\Framework\HTTP\Client\Curl;
use Magento\Framework\HTTP\ClientFactory;
use Magento\Framework\Serialize\SerializerInterface;
use Psr\Log\LoggerInterface;
use Vovchenko\PokemonApi\Client\PokeApiClient;
use Vovchenko\PokemonApi\Helper\ConfigData;

class PokeApiClientTest extends TestCase
{
    /**
     * @var Curl|MockObject
     */
    protected MockObject|Curl $curlMock;

    /**
     * @var ClientFactory|MockObject
     */
    protected MockObject|ClientFactory $clientFactoryMock;

    /**
     * @var MockObject|SerializerInterface
     */
    protected MockObject|SerializerInterface $serializerMock;

    /**
     * @var LoggerInterface|MockObject
     */
    protected LoggerInterface|MockObject $loggerMock;

    /**
     * @var ConfigData|MockObject
     */
    protected MockObject|ConfigData $configHelperMock;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        $this->curlMock = $this->getMockBuilder(Curl::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->clientFactoryMock = $this->getMockBuilder(ClientFactory::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->clientFactoryMock->method('create')->willReturn($this->curlMock);

        $this->serializerMock = $this->getMockBuilder(SerializerInterface::class)
            ->getMock();

        $this->loggerMock = $this->getMockBuilder(LoggerInterface::class)
            ->getMock();

        $this->configHelperMock = $this->getMockBuilder(ConfigData::class)
            ->disableOriginalConstructor()
            ->getMock();
    }

    /**
     * @return void
     */
    public function testFetchPokemonDataSuccess()
    {
        $pokemonData = [
            'name' => 'Pikachu', 'sprites' => ['other' => ['official-artwork' => ['front_default' => 'pikachu.jpg']]]
        ];
        $expectedResult = $pokemonData;

        $this->curlMock->method('getStatus')->willReturn(200);
        $this->curlMock->method('getBody')->willReturn(json_encode($pokemonData));
        $this->serializerMock->method('unserialize')->willReturn($pokemonData);

        $pokeApiClient = new PokeApiClient(
            $this->clientFactoryMock,
            $this->configHelperMock,
            $this->serializerMock,
            $this->loggerMock
        );

        $actualResult = $pokeApiClient->fetchPokemonData('pikachu');

        $this->assertEquals($expectedResult, $actualResult);
    }

    /**
     * @return void
     */
    public function testFetchPokemonDataFailure()
    {
        $expectedResult = null;

        $this->curlMock->method('getStatus')->willReturn(404);
        $this->curlMock->method('getBody')->willReturn('Not Found');

        $pokeApiClient = new PokeApiClient(
            $this->clientFactoryMock,
            $this->configHelperMock,
            $this->serializerMock,
            $this->loggerMock
        );

        $actualResult = $pokeApiClient->fetchPokemonData('pikachu');

        $this->assertEquals($expectedResult, $actualResult);
    }
}
