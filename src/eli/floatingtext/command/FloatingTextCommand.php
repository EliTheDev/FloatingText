<?php
declare(strict_types=1);

namespace eli\floatingtext\command;

use eli\floatingtext\Main;
use eli\floatingtext\libs\Commando\BaseCommand;
use eli\floatingtext\utils\Utils;
use eli\floatingtext\command\TpSubCommand;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;

class FloatingTextCommand extends BaseCommand
{
    public function prepare(): void
    {
        $this->setPermission("floatingtext.command");
        $this->registerSubCommand(new CreateSubCommand(Main::getInstance(), "create", "Create a new floating text"));
        $this->registerSubCommand(new DeleteSubCommand(Main::getInstance(), "delete", "Delete a floating text"));
        $this->registerSubCommand(new EditSubCommand(Main::getInstance(), "edit", "Edit a floating text"));
        $this->registerSubCommand(new TpSubCommand(Main::getInstance(), "tp", "Teleport to a floating text"));
    }

    public function onRun(CommandSender $sender, string $commandLabel, array $args): void
    {
        if (!$sender->hasPermission("floatingtext.command")) {
            $sender->sendMessage(Utils::getMessage("no-permission"));
            return;
        }

        if (!$sender instanceof Player) {
            $sender->sendMessage(Utils::getMessage("ingame-only"));
            return;
        }
        
        $sender->sendMessage(Utils::getMessage("help-separator"));
        $sender->sendMessage(Utils::getMessage("help-header"));
        $sender->sendMessage(Utils::getMessage("help-separator"));
        $sender->sendMessage(Utils::getMessage("help-create"));
        $sender->sendMessage(Utils::getMessage("help-delete"));
        $sender->sendMessage(Utils::getMessage("help-edit"));
        $sender->sendMessage(Utils::getMessage("help-tp"));
        $sender->sendMessage(Utils::getMessage("help-separator"));
    }
}
