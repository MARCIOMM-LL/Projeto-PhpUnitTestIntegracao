<?php

namespace Alura\Leilao\Tests\Integration\Dao;

use Alura\Leilao\Model\Leilao;
use Alura\Leilao\Dao\Leilao as LeilaoDao;
use PDO;
use PHPUnit\Framework\TestCase;

class LeilaoDaoTest extends TestCase
{
    //Novo método do php 7.4 que serve para definir tipos
    private static PDO $pdo;

    public static function setUpBeforeClass(): void
    {
        //O caminho :memory: indica que estou usando um
        //banco de dados em memória no sqlite
        self::$pdo = new PDO('sqlite::memory:');
        self::$pdo->exec('create table leiloes (
                id INTEGER primary key,
                descricao TEXT,
                finalizado BOOL,
                dataInicio TEXT
            );');
    }

    protected function setUp(): void
    {
        self::$pdo->beginTransaction();
    }

    /**
     * @dataProvider leiloes
     * @param array $leiloes
     */
    public function testBuscaLeiloesNaoFinalizados(array $leiloes)
    {
        //Arrange
        $leilaoDao = new LeilaoDao(self::$pdo);
        foreach ($leiloes as $leilao) {
            $leilaoDao->salva($leilao);
        }

        //Act
        $leiloes = $leilaoDao->recuperarFinalizados();

        //Assert
        self::assertCount(1, $leiloes);
        self::assertContainsOnlyInstancesOf(Leilao::class, $leiloes);
        self::assertSame('Fiat 147 0Km', $leiloes[0]->recuperarDescricao());
    }

    /**
     * @dataProvider leiloes
     * @param array $leiloes
     */
    public function testBuscaLeiloesFinalizados(array $leiloes)
    {
        //Arrange
        $leilaoDao = new LeilaoDao(self::$pdo);
        foreach ($leiloes as $leilao) {
            $leilaoDao->salva($leilao);
        }

        //Act
        $leiloes = $leilaoDao->recuperarFinalizados();

        //Assert
        self::assertCount(1, $leiloes);
        self::assertContainsOnlyInstancesOf(Leilao::class, $leiloes);
        self::assertSame('Fiat 147 0Km', $leiloes[0]->recuperarDescricao());
    }

    public function testAoAtualizarLeilaoStatusDeveSerAlterado()
    {
        //arrange
        $leilao = new Leilao('Brasília Amarela');
        $leilaoDao = new LeilaoDao(self::$pdo);
        $leilao = $leilaoDao->salva($leilao);

        $leiloes = $leilaoDao->recuperarNaoFinalizados();
        self::assertCount(1, $leiloes);
        self::assertSame('Brasília Amarela', $leiloes[0]->recuperarDescricao());
        self::assertFalse($leiloes[0]->estaFinalizado());

        $leilao->finaliza();
        //act
        $leilaoDao->atualiza($leilao);

        //assert
        $leiloes = $leilaoDao->recuperarFinalizados();
        self::assertCount(1, $leiloes);
        self::assertSame('Brasília Amarela', $leiloes[0]->recuperarDescricao());
        self::assertTrue($leiloes[0]->estaFinalizado());
    }

    protected function tearDown(): void
    {
        //Tear Down
        self::$pdo->rollBack();
    }

    public function leiloes()
    {
        $naoFinalizado = new Leilao('Variante 0Km');
        $finalizado = new Leilao('Fiat 147 0Km');
        $finalizado->finaliza();

        return [
          [
              [$naoFinalizado, $finalizado]
          ]
        ];
    }
}
