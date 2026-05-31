<?php
declare(strict_types=1);

namespace eli\floatingtext\command;

use eli\floatingtext\Main;
use eli\floatingtext\command\argument\FloatingTextEnumArgument;
use eli\floatingtext\form\DeleteFloatingTextForm;
use eli\floatingtext\libs\Commando\BaseSubCommand;
use eli\floatingtext\libs\Commando\constraint\InGameRequiredConstraint;
use eli\floatingtext\utils\Utils;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;

class DeleteSubCommand extends BaseSubCommand
{
    public function prepare(): void
    {
        $this->addConstraint(new InGameRequiredConstraint($this));
        $this->registerArgument(0, new FloatingTextEnumArgument(true, "floating_text"));
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

        if (!isset($args["floating_text"])) {
            new DeleteFloatingTextForm($sender);
            return;
        }

        $handler = Main::getInstance()->getHandler();

        if (is_null($text = $handler->getFloatingText($args["floating_text"]))) {
            $sender->sendMessage(Utils::getMessage("not-found"));
            return;
        }
        
        $handler->removeFloatingText($text->getIdentifier());
        $sender->sendMessage(Utils::getMessage("remove-success", ["id" => $text->getIdentifier()]));
    }
}
