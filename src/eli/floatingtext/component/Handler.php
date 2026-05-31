<?php
declare(strict_types=1);

namespace eli\floatingtext\component;

use eli\floatingtext\Main;

use pocketmine\world\Position;
use pocketmine\math\Vector3;

class Handler {
    /** @var FloatingText[] */
    private array $floatingTexts = [];
    
    public function __construct() {
        foreach (Main::getInstance()->getDatacenter()->prepare("rawData") as $id => $data){
            $worldName = $data["world"];
            $worldManager = Main::getInstance()->getServer()->getWorldManager();
            $world = $worldManager->getWorldByName($worldName);
            if ($world === null) {
                if ($worldManager->loadWorld($worldName)) {
                    $world = $worldManager->getWorldByName($worldName);
                }
            }
            if ($world === null) {
                Main::getInstance()->getLogger()->warning("Could not load world '{$worldName}' for floating text '{$id}'. It will not be loaded.");
                continue;
            }
            $this->floatingTexts[$id] = new FloatingText(
                Position::fromObject(new Vector3($data["x"], $data["y"], $data["z"]), $world),
                $id,
                $data["message"]
            );
        }
    }

    /**
     * @return FloatingText[]
     */
    public function getFloatingTexts(): array
    {
        return $this->floatingTexts;
    }

    public function getFloatingText(string $identifier): ?FloatingText
    {
        return $this->floatingTexts[$identifier] ?? null;
    }

    public function addFloatingText(FloatingText $text): void
    {
        $text->spawnToAll();
        $this->floatingTexts[$text->getIdentifier()] = $text;
        Main::getInstance()->getDatacenter()->save($text->getIdentifier(), $text->jsonSerialize());
        
        foreach (Main::getInstance()->getServer()->getOnlinePlayers() as $player) {
            $player->getNetworkSession()->syncAvailableCommands();
        }
    }

    public function removeFloatingText(string $identifier): void
    {
        if (isset($this->floatingTexts[$identifier])){
            $this->floatingTexts[$identifier]->despawnFromAll();
            unset($this->floatingTexts[$identifier]);
            Main::getInstance()->getDatacenter()->remove($identifier);
            
            foreach (Main::getInstance()->getServer()->getOnlinePlayers() as $player) {
                $player->getNetworkSession()->syncAvailableCommands();
            }
        }
    }
}