<?php
declare(strict_types=1);

namespace eli\floatingtext\form;

use eli\floatingtext\Main;
use eli\floatingtext\utils\Utils;
use eli\floatingtext\libs\form\ActionFormData;
use eli\floatingtext\libs\form\ActionFormResponse;
use pocketmine\player\Player;
use pocketmine\world\Position;

class EditSelectionForm
{
    public function __construct(private Player $player, private string $textId)
    {
        $handler = Main::getInstance()->getHandler();
        $floatingTexts = $handler->getFloatingTexts();

        if (!isset($floatingTexts[$this->textId])) {
            $player->sendMessage(Utils::getMessage("not-found"));
            return;
        }

        $form = ActionFormData::create()
            ->title(Utils::colorize("&6Edit: " . $this->textId))
            ->body(Utils::colorize("&7Choose what you want to edit for this floating text:\n&r"))
            ->divider()
            ->button(Utils::colorize("&eText\n&8Change the text"))
            ->divider()
            ->button(Utils::colorize("&ePosition\n&8Move to your location"))
            ->divider();

        $form->show($this->player)->then(function (Player $player, ActionFormResponse $response): void {
            if ($response->canceled) {
                return;
            }

            $handler = Main::getInstance()->getHandler();
            $floatingTexts = $handler->getFloatingTexts();

            if (!isset($floatingTexts[$this->textId])) {
                $player->sendMessage(Utils::getMessage("not-found"));
                return;
            }

            $text = $floatingTexts[$this->textId];

            if ($response->selection === 0) {
                new EditMessageForm($player, $this->textId);
            } elseif ($response->selection === 1) {
                $position = $player->getPosition();
                $text->despawnFromAll();
                $text->setPosition(new Position($position->getFloorX() + 0.5, $position->getFloorY() + 1, $position->getFloorZ() + 0.5, $position->getWorld()));
                $text->spawnToAll();

                // Save to database
                Main::getInstance()->getDatacenter()->save($this->textId, $text->jsonSerialize());

                $player->sendMessage(Utils::getMessage("update-success", ["id" => $this->textId]));
            }
        });
    }
}
