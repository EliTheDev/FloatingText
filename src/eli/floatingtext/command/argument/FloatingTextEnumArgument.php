<?php
declare(strict_types=1);

namespace eli\floatingtext\command\argument;

use eli\floatingtext\Main;
use eli\floatingtext\libs\Commando\args\StringEnumArgument;
use pocketmine\command\CommandSender;
use pocketmine\network\mcpe\protocol\AvailableCommandsPacket;
use pocketmine\network\mcpe\protocol\types\command\CommandParameter;
use pocketmine\network\mcpe\protocol\types\command\CommandHardEnum;

class FloatingTextEnumArgument extends StringEnumArgument
{
    protected const VALUES = [];

    public function __construct(bool $optional = true, ?string $name = null)
    {
        parent::__construct($name ?? "floating_text", $optional);
    }

    public function getEnumName(): string
    {
        return "id";
    }

    public function getTypeName(): string
    {
        return "floating_text";
    }

    public function getEnumValues(): array
    {
        $handler = Main::getInstance()->getHandler();
        $floatingTexts = $handler->getFloatingTexts();
        return array_keys($floatingTexts);
    }

    public function canParse(string $testString, CommandSender $sender): bool
    {
        $floatingTexts = Main::getInstance()->getHandler()->getFloatingTexts();
        return isset($floatingTexts[$testString]);
    }

    public function parse(string $argument, CommandSender $sender): string
    {
        return $argument;
    }

    public function getValue(string $string): string
    {
        return $string;
    }

    public function getNetworkParameterData(): CommandParameter
    {
        $values = $this->getEnumValues();
        if (count($values) === 0) {
            return CommandParameter::standard(
                $this->name,
                AvailableCommandsPacket::ARG_TYPE_STRING,
                0,
                $this->optional
            );
        }
        return CommandParameter::enum(
            $this->name,
            new CommandHardEnum($this->getEnumName(), $values),
            0,
            $this->optional
        );
    }
}
