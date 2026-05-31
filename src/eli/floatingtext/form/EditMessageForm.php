<?php
declare(strict_types=1);

namespace eli\floatingtext\form;

use eli\floatingtext\Main;
use eli\floatingtext\utils\Utils;
use eli\floatingtext\libs\form\ModalFormData;
use eli\floatingtext\libs\form\ModalFormResponse;
use pocketmine\player\Player;

class EditMessageForm
{
    public function __construct(private Player $player, private string $textId)
    {
        $handler = Main::getInstance()->getHandler();
        $floatingTexts = $handler->getFloatingTexts();

        if (!isset($floatingTexts[$this->textId])) {
            $player->sendMessage(Utils::getMessage("not-found"));
            return;
        }

        $text = $floatingTexts[$this->textId];
        $currentMessage = $text->getMessage();

        $form = ModalFormData::create()
            ->title(Utils::colorize("&6Edit Message: " . $this->textId))
            ->label(Utils::colorize("&7Edit the message/text for &e" . $this->textId))
            ->divider()
            ->textField(Utils::colorize("&eNew Message:"), $currentMessage, "")
            ->divider()
            ->submitButton(Utils::colorize("&aSave Changes"));

        $form->show($this->player)->then(function (Player $player, ModalFormResponse $response): void {
            if ($response->canceled) {
                $player->sendMessage(Utils::getMessage("form-cancelled"));
                return;
            }

            $values = $response->formValues;
            $newMessage = trim($values[2] ?? "");

            if (empty($newMessage)) {
                $player->sendMessage(Utils::getMessage("message-empty"));
                new EditMessageForm($player, $this->textId);
                return;
            }

            $handler = Main::getInstance()->getHandler();
            $floatingTexts = $handler->getFloatingTexts();

            if (!isset($floatingTexts[$this->textId])) {
                $player->sendMessage(Utils::getMessage("not-found"));
                return;
            }

            $text = $floatingTexts[$this->textId];

            // Update message
            $text->setMessage($newMessage);
            $text->update();

            // Save to database
            Main::getInstance()->getDatacenter()->save($this->textId, $text->jsonSerialize());

            // Update all players
            $text->sendChangesToAll();

            $player->sendMessage(Utils::getMessage("update-success", ["id" => $this->textId]));
        });
    }
}
