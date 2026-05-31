<?php
declare(strict_types=1);

namespace eli\floatingtext\listener;

use eli\floatingtext\Main;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\event\player\PlayerQuitEvent;
use pocketmine\event\entity\EntityTeleportEvent;
use pocketmine\player\Player;
use pocketmine\scheduler\ClosureTask;

class EventListener implements Listener
{
    public function onJoin(PlayerJoinEvent $event): void
    {
        $player = $event->getPlayer();
        $handler = Main::getInstance()->getHandler();
        
        foreach ($handler->getFloatingTexts() as $text) {
            $text->spawn($player);
        }

        Main::getInstance()->getScheduler()->scheduleDelayedTask(new ClosureTask(function(): void {
            $handler = Main::getInstance()->getHandler();
            foreach ($handler->getFloatingTexts() as $text) {
                $text->sendChangesToAll();
            }
        }), 1);
    }

    public function onQuit(PlayerQuitEvent $event): void
    {
        Main::getInstance()->getScheduler()->scheduleDelayedTask(new ClosureTask(function(): void {
            $handler = Main::getInstance()->getHandler();
            foreach ($handler->getFloatingTexts() as $text) {
                $text->sendChangesToAll();
            }
        }), 1);
    }

    public function onTeleport(EntityTeleportEvent $event): void
    {
        $player = $event->getEntity();
        if ($player instanceof Player) {
            if (!$event->isCancelled()) {
                $toWorld = $event->getTo()->getWorld();
                Main::getInstance()->getScheduler()->scheduleDelayedTask(new ClosureTask(function() use ($player, $toWorld): void {
                    if ($player->isOnline() && $player->getWorld() === $toWorld) {
                        $handler = Main::getInstance()->getHandler();
                        foreach ($handler->getFloatingTexts() as $text) {
                            $text->spawn($player);
                        }
                    }
                }), 1);
            }
        }
    }
}
