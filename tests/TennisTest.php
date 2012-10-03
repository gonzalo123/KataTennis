<?php

use Tennis\Match;
use Tennis\WinException;

class TennisTest extends PHPUnit_Framework_TestCase
{
    /** @var Tennis\Match */
    private $match;

    public function setUp()
    {
        $this->match = new Match('Gonzalo', 'Peter');
    }

    public function testInitGame()
    {
        $this->assertEquals('0', $this->match->getScoreboardForPlayer1());
        $this->assertEquals('0', $this->match->getScoreboardForPlayer2());
    }

    public function testPlayer1Scoreboard()
    {
        $this->assertEquals('0', $this->match->getScoreboardForPlayer1());
        $this->match->ballToPlayer1();
        $this->assertEquals('15', $this->match->getScoreboardForPlayer1());
        $this->match->ballToPlayer1();
        $this->assertEquals('30', $this->match->getScoreboardForPlayer1());
    }

    public function testPlayer2Scoreboard()
    {
        $this->assertEquals('0', $this->match->getScoreboardForPlayer2());
        $this->match->ballToPlayer2();
        $this->assertEquals('15', $this->match->getScoreboardForPlayer2());
        $this->match->ballToPlayer2();
        $this->assertEquals('30', $this->match->getScoreboardForPlayer2());
    }

    public function testBallsForTwoPlayers()
    {
        $this->assertEquals('0', $this->match->getScoreboardForPlayer1());
        $this->assertEquals('0', $this->match->getScoreboardForPlayer2());
        $this->match->ballToPlayer2();

        $this->assertEquals('0', $this->match->getScoreboardForPlayer1());
        $this->assertEquals('15', $this->match->getScoreboardForPlayer2());
        $this->match->ballToPlayer1();

        $this->assertEquals('15', $this->match->getScoreboardForPlayer1());
        $this->assertEquals('15', $this->match->getScoreboardForPlayer2());
    }

    public function testDeuce()
    {
        $this->match->ballToPlayer1();
        $this->match->ballToPlayer1();
        $this->match->ballToPlayer1();

        $this->match->ballToPlayer2();
        $this->match->ballToPlayer2();

        $this->assertEquals(FALSE, $this->match->isDeuce());
        $this->match->ballToPlayer2();

        $this->assertEquals('40', $this->match->getScoreboardForPlayer1(), 'P1');
        $this->assertEquals('40', $this->match->getScoreboardForPlayer2(), 'P2');

        $this->assertEquals(TRUE, $this->match->isDeuce());
    }

    public function testAdvance()
    {
        $this->match->ballToPlayer1();
        $this->match->ballToPlayer1();
        $this->match->ballToPlayer1();

        $this->match->ballToPlayer2();
        $this->match->ballToPlayer2();
        $this->match->ballToPlayer2();
        $this->assertEquals('40', $this->match->getScoreboardForPlayer2());
        $this->assertEquals(TRUE, $this->match->isDeuce());

        $this->match->ballToPlayer2();
        $this->assertEquals('40', $this->match->getScoreboardForPlayer1());
        $this->assertEquals('40+', $this->match->getScoreboardForPlayer2());

        $this->match->ballToPlayer1();
        $this->assertEquals('40', $this->match->getScoreboardForPlayer1());
        $this->assertEquals('40', $this->match->getScoreboardForPlayer2());

        $this->match->ballToPlayer1();
        $this->assertEquals('40+', $this->match->getScoreboardForPlayer1());
        $this->assertEquals('40', $this->match->getScoreboardForPlayer2());
    }

    public function testPlayer1WinsAfterDeuce()
    {
        try {
            $this->match->ballToPlayer1();
            $this->match->ballToPlayer1();
            $this->match->ballToPlayer1();

            $this->match->ballToPlayer2();
            $this->match->ballToPlayer2();
            $this->match->ballToPlayer2();
            $this->assertEquals(TRUE, $this->match->isDeuce());
            $this->match->ballToPlayer1();
            $this->assertEquals(TRUE, $this->match->isDeuce());
            $this->match->ballToPlayer1();
            $this->fail("Unrecheable code");
        } catch (WinException $e) {
            $this->assertEquals("Gonzalo Wins", $e->getMessage());
        }
    }

    /**
     * @expectedException Tennis\WinException
     */
    public function testPlayer1Wins()
    {
        $this->match->ballToPlayer1();
        $this->match->ballToPlayer1();
        $this->match->ballToPlayer1();
        $this->match->ballToPlayer1();

    }

    /**
     * @expectedException Tennis\WinException
     */
    public function testPlayer2Wins()
    {
        $this->match->ballToPlayer2();
        $this->match->ballToPlayer2();
        $this->match->ballToPlayer2();
        $this->match->ballToPlayer2();
    }
}