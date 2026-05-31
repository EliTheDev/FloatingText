<?php
declare(strict_types=1);

namespace eli\floatingtext\command;

use eli\floatingtext\form\CreateFloatingTextForm;
use eli\floatingtext\libs\Commando\BaseSubCommand;
use eli\floatingtext\libs\Commando\constraint\InGameRequiredConstraint;
use eli\floatingtext\utils\Utils;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;

class CreateSubCommand extends BaseSubCommand
{
    public function prepare(): void
    {
        $this->addConstraint(new InGameRequiredConstraint($this));
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

        new CreateFloatingTextForm($sender);
    }
}
