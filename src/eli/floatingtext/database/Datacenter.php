<?php
declare(strict_types=1);

namespace eli\floatingtext\database;

use eli\floatingtext\Main;

class Datacenter {
    private array $languageData = [];
    private array $floatingTextData = [];
    private string $dataPath;

    public function __construct()
    {
        $this->languageData = yaml_parse_file(Main::getInstance()->getDataFolder() . "texts.yml");

        $this->dataPath = Main::getInstance()->getDataFolder() . "data.json";
        if (file_exists($this->dataPath)) {
            $this->floatingTextData = json_decode(file_get_contents($this->dataPath), true) ?? [];
        } else {
            $this->floatingTextData = [];
        }
    }

    public function prepare(string $key): array
    {
        if ($key === "language") {
            return $this->languageData["language"] ?? [];
        }
        return $this->floatingTextData;
    }

    public function remove(string $key): void
    {
        if (isset($this->floatingTextData[$key])){
            unset($this->floatingTextData[$key]);
            file_put_contents($this->dataPath, json_encode($this->floatingTextData, JSON_PRETTY_PRINT));
        }
    }

    public function save(string $key, array $value): void
    {
        $this->floatingTextData[$key] = $value;
        file_put_contents($this->dataPath, json_encode($this->floatingTextData, JSON_PRETTY_PRINT));
    }
}