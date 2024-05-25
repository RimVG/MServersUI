<?php

namespace RimVG\PluginDev;

use pocketmine\plugin\PluginBase;
use pocketmine\player\Player;
use pocketmine\event\Listener;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use jojoe77777\FormAPI\SimpleForm;
use pocketmine\utils\TextFormat;

class Main extends PluginBase implements Listener {

    public function onEnable(): void {
        $this->getLogger()->info("Done Plugin MServerUI Active!");
        $this->saveDefaultConfig();
        $this->getServer()->getPluginManager()->registerEvents($this, $this);
    }

    public function onCommand(CommandSender $sender, Command $command, string $label, array $args): bool {
        if (strtolower($command->getName()) === "msi" && $sender instanceof Player) {
            $this->openUi($sender);
            return true;
        }
        $sender->sendMessage(TextFormat::RED . "This MServerUI command can only be used in game.");
        return false;
    }

    private function openUi(Player $player): void {
        $config = $this->getConfig()->get("msi");
        $form = new SimpleForm(function(Player $player, ?int $data) use ($config) {
            if ($data !== null && isset($config["buttons"][$data]["command"])) {
                $this->getServer()->dispatchCommand($player, $config["buttons"][$data]["command"]);
            }
        });

        $form->setTitle($config["title"] ?? "MServerUI");
        $form->setContent($config["content"] ?? "Select an option:");
        foreach ($config["buttons"] as $button) {
            $form->addButton($button["name"], 1, $button["icon"] ?? "");
        }

        $player->sendForm($form);
    }
}