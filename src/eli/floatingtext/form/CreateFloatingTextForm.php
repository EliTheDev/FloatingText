<?php
declare(strict_types=1);

namespace eli\floatingtext\form;

use eli\floatingtext\Main;
use eli\floatingtext\component\FloatingText;
use eli\floatingtext\utils\Utils;
use eli\floatingtext\libs\form\ModalFormData;
use eli\floatingtext\libs\form\ModalFormResponse;
use pocketmine\player\Player;
use pocketmine\world\Position;

class CreateFloatingTextForm
{
    public function __construct(private Player $player)
    {
        $form = ModalFormData::create()
            ->title(Utils::colorize("&6Create Floating Text"))
            ->label(Utils::colorize("&7Configure the details for the new floating text below:"))
            ->divider()
            ->textField(Utils::colorize("&eID:"), "my_text", "")
            ->divider()
            ->textField(Utils::colorize("&eMessage:"), "Hello World!", "")
            ->submitButton(Utils::colorize("&aCreate Text"));

        $form->show($this->player)->then(function (Player $player, ModalFormResponse $response): void {
            if ($response->canceled) {
                $player->sendMessage(Utils::getMessage("form-cancelled"));
                return;
            }

            $values = $response->formValues;
            $id = trim($values[2] ?? "");
            $text = trim($values[4] ?? "");

            if (empty($id)) {
                $player->sendMessage(Utils::getMessage("id-empty"));
                new CreateFloatingTextForm($player);
                return;
            }

            if (empty($text)) {
                $player->sendMessage(Utils::getMessage("message-empty"));
                new CreateFloatingTextForm($player);
                return;
            }

            $handler = Main::getInstance()->getHandler();
            $floatingTexts = $handler->getFloatingTexts();
            if (isset($floatingTexts[$id])) {
                $player->sendMessage(Utils::getMessage("id-exists", ["id" => $id]));
                new CreateFloatingTextForm($player);
                return;
            }

            $position = $player->getPosition();

            $floatingText = new FloatingText(
                new Position($position->getX(), $position->getY() + 1, $position->getZ(), $position->getWorld()),
                $id,
                $text
            );

            $handler->addFloatingText($floatingText);

            $player->sendMessage(Utils::getMessage("create-success", ["id" => $id]));
            $player->sendMessage(Utils::getMessage("message-info", ["message" => $text]));
        });
    }
}
