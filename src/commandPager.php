<?php

declare(strict_types=1);

namespace folosuru\commandPager;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\event\Listener;
use pocketmine\player\Player;
use pocketmine\plugin\PluginBase;

class commandPager extends PluginBase implements Listener {
	private static $instance;
	public $pages = [];
	public static function getInstance() : commandPager{
		return self::$instance;
	}

	public function getPager($player){
		if (array_key_exists($player->getName(),$this->pages)){
			return $this->pages[$player->getName()];
		}else{
			return $this->pages[$player->getName()] = new Pages($player);
		}
	}

	public function onEnable(): void{
		$this->getServer()->getPluginManager()->registerEvents($this,$this);
		$this->pages = array();
	}

	public function onCommand(CommandSender $sender, Command $command, string $label, array $args): bool{
		switch ($command->getName()) {
            case 'next':
                $page = $this->getPager($sender);
                if (!$page->getIndex() + 1 == $page->getPageCount()) {
                    $page->setIndex($page->getIndex() + 1)->sendMessage($sender);
                } else {
                    $sender->sendMessage('表示するものがありません');
                }
                return true;

            case 'prev':
                $page = $this->getPager($sender);
                if (!$page->getIndex() == 0) {
                    $page->setIndex($page->getIndex() - 1)->sendMessage($sender);
                } else {
                    $sender->sendMessage('表示するものがありません');
                }
                return true;
            case 'nowpage':
                $this->getPager($sender)->sendMessage($sender);
                return true;

            case "select":
                if (count($args) == 1) {
                    if (!is_countable($args[0])) {
                        $sender->sendMessage("P1 not countable");
                    }
                    $this->getPager($sender)->onSelect( ((int) $args[0])-1);
                }
        }
        return true;
	}

	public function onLoad(): void{
		if(!self::$instance instanceof commandPager){
			self::$instance = $this;
		}
	}

}
