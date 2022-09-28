<?php

namespace folosuru\commandPager;

use pocketmine\command\CommandSender;
use pocketmine\player\Player;

abstract class commandPagerActionBase{

    protected string $selectContentText;
    protected array|null $selectContentData;
    protected int $index;
    private CommandSender $player;

    final public function setStatus(string $text, int $index ,mixed $data,CommandSender $player) : commandPagerActionBase{
        $this->selectContentText = $text;
        $this->index = $index;
        $this->selectContentData = $data;
        $this->player = $player;
        return $this;
    }

    public function getPlayer() : CommandSender{
        return $this->player;
    }

    public function getSelectContentData(): mixed{
        if (isset($this->selectContentData)){
        if (array_key_exists($this->index,$this->selectContentData)){
             return $this->selectContentData[$this->index];
         }else{
             return null;
        }
        }
		return null;
    }

    public function getSelectContentText(): string{
        return $this->selectContentText;
    }

    public function getIndex(): int{
        return $this->index;
    }

    abstract public function onRun():void;

}