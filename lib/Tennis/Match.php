<?php

namespace Tennis;

use Tennis\WinException;

class Match
{
    private $playerName;

    const PLAYER1 = 0;
    const PLAYER2 = 1;
    private $counter = array(0, 15, 30, 40);
    private $matchCounter = array(0, 0);
    private $advance = array(FALSE, FALSE);

    public function __construct($player1Name, $player2Name)
    {
        $this->playerName[self::PLAYER1] = $player1Name;
        $this->playerName[self::PLAYER2] = $player2Name;
    }

    public function getScoreboardForPlayer1()
    {
        return $this->getScoreboard(self::PLAYER1);
    }

    public function getScoreboardForPlayer2()
    {
        return $this->getScoreboard(self::PLAYER2);
    }

    public function ballToPlayer1()
    {
        $this->ballToPlayer(self::PLAYER1);
    }

    public function ballToPlayer2()
    {
        $this->ballToPlayer(self::PLAYER2);
    }

    private function getScoreboard($player)
    {
        $scoreBoard = $this->counter[$this->matchCounter[$player]];
        if ($this->playerHasAdvance($player)) $scoreBoard .= '+';
        return $scoreBoard;
    }

    private function playerHasAdvance($player)
    {
        return $this->advance[$player];
    }

    public function isDeuce()
    {
        return ($this->matchCounter[self::PLAYER1] == $this->matchCounter[self::PLAYER2] &&
                $this->matchCounter[self::PLAYER2] == 3);
    }

    private function ballToPlayer($player)
    {
        if ($this->matchCounter[$player] == 3 && !$this->isDeuce()) {
            throw new WinException($this->playerName[$player] . " Wins");
        }

        if (!$this->isDeuce()) {
            $this->matchCounter[$player]++;
        } else {
            if ($this->playerHasAdvance($player)) throw new WinException($this->playerName[$player] . " Wins");
            $this->setAdvanceToPlayer($player);
        }
    }

    private function setAdvanceToPlayer($player)
    {
        $theOtherPlayer = !$player;
        if ($this->playerHasAdvance($theOtherPlayer)) {
            $this->advance = array(FALSE, FALSE);
        } else {
            $this->advance[$player] = TRUE;
        }
    }
}