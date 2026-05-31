<?php
declare(strict_types=1);

namespace eli\floatingtext;

use eli\floatingtext\database\Datacenter;
use eli\floatingtext\component\Handler;
use eli\floatingtext\command\FloatingTextCommand;
use eli\floatingtext\libs\Commando\PacketHooker;
use eli\floatingtext\listener\EventListener;

use pocketmine\plugin\PluginBase;
use pocketmine\utils\SingletonTrait;

class Main extends PluginBase {
    use SingletonTrait;

    private Datacenter $datacenter;

    private Handler $handler;

    public function onLoad(): void {
        self::setInstance($this);
        $this->saveResource("texts.yml");
    }

    public function onEnable(): void {
        $this->datacenter = new Datacenter();
        $this->handler = new Handler();
        if (!PacketHooker::isRegistered()){
            PacketHooker::register($this);
        }
        
        \eli\floatingtext\libs\form\PMServerUI::register($this);
        
        $this->getServer()->getPluginManager()->registerEvents(new EventListener(), $this);
        
        $this->getServer()->getCommandMap()->register($this->getName(), new FloatingTextCommand($this, "floatingtext", "FloatingText command", ["ft"]));
    }

    public function getDatacenter(): Datacenter {
        return $this->datacenter;
    }

    public function getHandler(): Handler {
        return $this->handler;
    }
}