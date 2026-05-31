<?php
declare(strict_types=1);

namespace eli\floatingtext\utils;

use eli\floatingtext\Main;
use pocketmine\utils\TextFormat;

class Utils {
    public static function colorize(string $message): string
    {
        return TextFormat::colorize(str_replace("\\n", "\n", $message));
    }

    public static function clean(string $message): string
    {
        return TextFormat::clean($message);
    }

    public static function formatMessage(string $message, array $replacements): string
    {
        foreach ($replacements as $key => $value){
            $message = str_replace("{" . $key . "}", (string)$value, $message);
        }
        return $message;
    }

    public static function constructMessage(string $message, array $replacements): string
    {
        return self::colorize(Main::getInstance()->getDatacenter()->prepare("language")["prefix"] . self::formatMessage($message, $replacements));
    }

    public static function getMessage(string $key, array $replacements = []): string
    {
        $language = Main::getInstance()->getDatacenter()->prepare("language");
        $message = $language[$key] ?? "&cMessage key '{$key}' not found!";
        return self::constructMessage($message, $replacements);
    }
}