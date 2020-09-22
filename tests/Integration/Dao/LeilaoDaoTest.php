<?php

namespace Alura\Leilao\Tests\Integration\Dao;

use Alura\Leilao\Infra\ConnectionCreator;
use Alura\Leilao\Model\Leilao;
use Alura\Leilao\Dao\Leilao as LeilaoDao;
use PHPUnit\Framework\TestCase;

class LeilaoDaoTest extends TestCase
{
    //Novo método do php 7.4 que serve para definir tipos
    private \PDO $pdo;

    protected function setUp(): void
    {
        //O caminho :memory: indica que estou usando um
        //banco de dados em memória no sqlite
        $this->pdo = new \PDO('sqlite::memory:');
        $this->pdo->beginTransaction();
    }

    public function testInsercaoEBuscaDevemFuncionar()
    {
        //Arrange
        $leilao = new Leilao('Variante 0Km');
        $leilaoDao = new LeilaoDao($this->pdo);
        $leilaoDao->salva($leilao);

        //Act
        $leiloes = $leilaoDao->recuperarNaoFinalizados();

        //Assert
        self::assertCount(1, $leiloes);
        self::assertContainsOnlyInstancesOf(Leilao::class, $leiloes);
        self::assertSame('Variante 0Km', $leiloes[0]->recuperarDescricao());

        //Tear Down
        $this->pdo->rollBack();
    }
}