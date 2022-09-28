<?php

namespace folosuru\commandPager;

use pocketmine\command\CommandSender;
use pocketmine\console\ConsoleCommandSender;
use pocketmine\player\Player;

class Pages{

    private int $index = 0;

    private array $pages;

    private int $height = 5;

	private string $title;

	private CommandSender $player;

    private $listing;

	/** example
 	 * height:6
	 * page1: 0,1,2,3,4,5
	 * page2: 6,7,8,9,10,11
	 * page3: 12,13,14,15,16,17
	 * ...
 	*/
    private array|null  $actiondata;
    private commandPagerActionBase|null $commandAction;


    public function __construct(CommandSender $player){
		$this->player = $player;
	}


    /**
     * @param string[] $pages
     * @param string $title
     * @param array |null $actionData
     * @return Pages
     */
    public function newPages(array $pages,string $title,?array $actionData=null):Pages{
        unset($this->commandAction);
        $this->index = 0;
        $this->pages = $pages;
		$this->title = $title;
        $this->actiondata = $actionData;
        $this->listing = false;
        return $this;
    }

    public function getIndex() : int{
        return $this->index;
    }

    public function setIndex(int $index):Pages{
        $this->index = $index;
		return $this;
    }

	public function getTitle():string{
		return $this->title;
	}

	public function setHeight(int $height){
		$this->height = $height;
	}

	public function getLineCount(): int{
		return count($this->pages);
	}
	public function getPageCount(): int{
		return ceil(count($this->pages)/$this->height);
	}

    public function existPage(int $index) : bool{
        return array_key_exists($this->index*$this->height,$this->pages);
    }

    public function getPage() : array|false {
        if (empty($this->pages)){return false;}
        $cnt =0;
        $result = array();
        while ($cnt < $this->height){
            if (array_key_exists(($this->index*$this->height) + $cnt,$this->pages)) {
                $result[] = $this->pages[($this->index * $this->height) + $cnt];
            }
            $cnt++;
        }
        return $result;
    }

    public function setAction(commandPagerActionBase $action) : Pages{
        $this->commandAction = $action;
        $this->listing = true;
        return $this;
    }

    public function onSelect(int $index){
        if (isset($this->commandAction)){
            $this->commandAction->setStatus(
                $this->pages[($this->index*$this->height) + $index],
                ($this->index*$this->height) + $index,
                $this->actiondata,
                $this->player
            )
            ->onRun();

        }
    }

	public function sendMessage(){
		$player = $this->player;
		$pages = $this->getPage();
		$player->sendMessage('----' . $this->getTitle() . ' ----');
        $count = 1;
		foreach ($pages as $value) {
            if ($this->listing){
                $player->sendMessage($count ." : " .$value);
            }else {
                $player->sendMessage($value);
            }
            $count += 1;
		}
		if ($this->getIndex() + 1 == $this->getPageCount()) {
			$str = 'last  -|';
		} else {
			$str = '/next ->';
		}
		if ($this->getIndex() == 0) {
			$str2 = '|- first';
		} else {
			$str2 = '<- /prev';
		}
		$player->sendMessage($str2 . ' |   ' . $this->getIndex() + 1 . "/" . $this->getPageCount() . '   | ' . $str);
	}
}