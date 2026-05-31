<?php
declare(strict_types=1);

namespace eli\floatingtext\form;

use eli\floatingtext\Main;
use eli\floatingtext\utils\Utils;
use eli\floatingtext\libs\form\ActionFormData;
use eli\floatingtext\libs\form\ActionFormResponse;
use pocketmine\player\Player;

class DeleteFloatingTextForm
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
            ->title(Utils::colorize("&6Delete Floating Text"))
            ->body(Utils::colorize("&7Select a floating text to remove permanently:\n&r"))
            ->divider();

        $texts = [];
        foreach ($floatingTexts as $id => $text) {
            $message = $text->getMessage();
            if (strlen($message) > 30) {
                $message = substr($message, 0, 27) . "...";
            }
            $form->button(Utils::colorize("&e" . $id . "\n&8" . $message));
            $form->divider();
            $texts[] = $id;
        }

        $form->show($this->player)->then(function (Player $player, ActionFormResponse $response) use ($handler, $texts): void {
            if ($response->canceled) {
                return;
            }

            $selection = $response->selection;
            if (!isset($texts[$selection])) {
                $player->sendMessage(Utils::getMessage("invalid-selection"));
                return;
            }

            $id = $texts[$selection];
            $handler->removeFloatingText($id);

            $player->sendMessage(Utils::getMessage("remove-success", ["id" => $id]));
        });
    }
}
