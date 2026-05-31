<?php
declare(strict_types=1);

namespace eli\floatingtext\form;

use eli\floatingtext\Main;
use eli\floatingtext\utils\Utils;
use eli\floatingtext\libs\form\ActionFormData;
use eli\floatingtext\libs\form\ActionFormResponse;
use pocketmine\player\Player;

class EditFloatingTextForm
{
    public function __construct(private Player $player)
    {
        $handler = Main::getInstance()->getHandler();
        $floatingTexts = $handler->getFloatingTexts();

        if (empty($floatingTexts)) {
            $player->sendMessage(Utils::getMessage("no-texts-found"));
            return;
        }

        $form = ActionFormData::create()
            ->title(Utils::colorize("&6Edit Floating Text"))
            ->body(Utils::colorize("&7Select a floating text to edit:\n&r"))
            ->divider();

        $texts = [];
        foreach ($floatingTexts as $id => $text) {
            $message = Utils::clean($text->getMessage());
            if (strlen($message) > 30) {
                $message = substr($message, 0, 27) . "...";
            }
            $form->button(Utils::colorize("&e" . $id . "\n&8" . $message));
            $form->divider();
            $texts[] = $id;
        }

        $form->show($this->player)->then(function (Player $player, ActionFormResponse $response) use ($texts): void {
            if ($response->canceled) {
                return;
            }

            $selection = $response->selection;
            if (!isset($texts[$selection])) {
                $player->sendMessage(Utils::getMessage("invalid-selection"));
                return;
            }

            $id = $texts[$selection];
            new EditSelectionForm($player, $id);
        });
    }
}
