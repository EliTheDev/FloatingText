<?php
declare(strict_types=1);

namespace eli\floatingtext\component;

use pocketmine\player\Player;
use pocketmine\world\Position;
use pocketmine\world\World;
use eli\floatingtext\Main;
use eli\floatingtext\utils\Utils;

class FloatingText extends \pocketmine\world\particle\FloatingTextParticle {

    /** @var string */
    private string $identifier;

    /** @var string */
    private string $message;

    /** @var World */
    private World $world;

    /** @var Position */
    private Position $position;

    /**
     * FloatingText constructor.
     *
     * @param Position $pos
     * @param string $identifier
     * @param string $message
     */
    public function __construct(Position $pos, string $identifier, string $message) {
        parent::__construct("", $message);
        $this->world = $pos->getWorld();
        $this->identifier = $identifier;
        $this->message = $message;
        $this->position = $pos;
    }


    /**
     * @return Position
     */
    public function getPosition(): Position
    {
        return $this->position;
    }
    /**
     * @param Position $position
     */
    public function setPosition(Position $position): void
    {
        $this->position = $position;
        $this->world = $position->getWorld();
    }
    /**
     * @param string $message
     */
    public function setMessage(string $message): void
    {
        $this->message = $message;
    }
    /**
     * @return string
     */
    public function getMessage(): string {
        return $this->message;
    }

    /**
     * @return World
     */
    public function getWorld(): World {
        return $this->world;
    }

    /**
     * @param null|string $message
     */
    public function update(?string $message = null): void {
        $this->message = $message ?? $this->message;
        $this->setTitle($this->message);
    }

    /**
     * @return string
     */
    public function getIdentifier(): string {
        return $this->identifier;
    }

    /**
     * @param Player $player
     */
    public function sendChangesTo(Player $player): void {
        $this->setTitle($this->getFormattedMessage($player));
        $level = $this->getWorld();
        if($level === null) {
            return;
        }
        if($this->world->getFolderName() !== $player->getWorld()->getFolderName()) {
            return;
        }
        $this->world->addParticle($this->position, $this, [$player]);
    }

    public function sendChangesToAll(): void {
        foreach ($this->world->getPlayers() as $player){
            if($this->world->getFolderName() !== $player->getWorld()->getFolderName()) {
                continue;
            }
            $this->setTitle($this->getFormattedMessage($player));
            $this->world->addParticle($this->position, $this, [$player]);
        }
    }

    /**
     * @param Player $player
     */
    public function spawn(Player $player): void {
        $this->setInvisible(false);
        $level = $this->getWorld();
        if($level === null) {
            return;
        }
        if($this->world->getFolderName() !== $player->getWorld()->getFolderName()) {
            return;
        }
        $this->setTitle($this->getFormattedMessage($player));
        $this->world->addParticle($this->position, $this, [$player]);
    }

    public function spawnToAll(): void {
        $this->setInvisible(false);
        foreach ($this->world->getPlayers() as $player){
            if($this->world->getFolderName() !== $player->getWorld()->getFolderName()) {
                continue;
            }
            $this->setTitle($this->getFormattedMessage($player));
            $this->world->addParticle($this->position, $this, [$player]);
        }
    }

    public function getFormattedMessage(Player $player): string
    {
        $server = Main::getInstance()->getServer();
        $placeholders = [
            "{player}" => $player->getName(),
            "{online}" => (string)count($server->getOnlinePlayers()),
            "{max_online}" => (string)$server->getMaxPlayers()
        ];
        $message = str_replace(array_keys($placeholders), array_values($placeholders), $this->message);
        return Utils::colorize($message);
    }

    /**
     * @param Player $player
     */
    public function despawn(Player $player): void {
        $this->setInvisible();
        $level = $this->getWorld();
        if($level === null) {
            return;
        }
        if($this->world->getFolderName() !== $player->getWorld()->getFolderName()) {
            return;
        }
        $this->world->addParticle(new Position($this->position->getX(), $this->position->getY(), $this->position->getZ(), $this->position->getWorld()), $this, [$player]);
    }

    public function despawnFromAll(): void {
        $this->setInvisible();
        foreach ($this->world->getPlayers() as $player){
            if($this->world->getFolderName() !== $player->getWorld()->getFolderName()) {
                continue;
            }
            $this->world->addParticle(new Position($this->position->getX(), $this->position->getY(), $this->position->getZ(), $this->position->getWorld()), $this, [$player]);
        }
    }

    public function jsonSerialize(): array {
        return [
            "message" => $this->message,
            "x" => $this->position->getX(),
            "y" => $this->position->getY(),
            "z" => $this->position->getZ(),
            "world" => $this->position->getWorld()->getFolderName()
        ];
    }
}