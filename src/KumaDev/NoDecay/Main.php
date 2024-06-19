<?php

namespace KumaDev\NoDecay;

use pocketmine\plugin\PluginBase;
use pocketmine\event\Listener;
use pocketmine\event\block\LeavesDecayEvent;
use pocketmine\event\block\BlockBreakEvent;
use pocketmine\block\Block;
use pocketmine\block\VanillaBlocks;
use pocketmine\utils\Config;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;
use KumaDev\NoDecay\Farm\FarmManager;

class Main extends PluginBase implements Listener {

    private Config $config;
    private bool $noDecayEnabled;

    public function onEnable(): void {
        $this->saveDefaultConfig();
        $this->config = $this->getConfig();
        $this->noDecayEnabled = true; // Default to enabled
        $this->getServer()->getPluginManager()->registerEvents($this, $this);
        $this->getServer()->getPluginManager()->registerEvents(new FarmManager($this), $this);
    }

    public function onCommand(CommandSender $sender, Command $command, string $label, array $args): bool {
        if ($command->getName() === "nodecay") {
            if (!$sender instanceof Player) {
                $sender->sendMessage("§cThis command can only be used in-game.");
                return true;
            }

            if (count($args) !== 1) {
                $sender->sendMessage("§eUsage: §f/nodecay [on/off]");
                return true;
            }

            switch (strtolower($args[0])) {
                case "on":
                    $this->noDecayEnabled = true;
                    $sender->sendMessage("§aNoDecay berhasil di aktifkan.");
                    break;
                case "off":
                    $this->noDecayEnabled = false;
                    $sender->sendMessage("§cNoDecay berhasil di nonaktifkan");
                    break;
                default:
                    $sender->sendMessage("§eGunakan: §f/nodecay [on/off]");
                    break;
            }
            return true;
        }
        return false;
    }

    public function onLeavesDecay(LeavesDecayEvent $event): void {
        if (!$this->noDecayEnabled) {
            return;
        }

        $block = $event->getBlock();
        $world = $block->getPosition()->getWorld()->getDisplayName();

        if ($this->config->get("NoDecay-Leaves", true)) {
            if ($this->config->get("AllWorld-NoDecay-Leaves", true) || 
                in_array($world, $this->config->get("World-NoDecay-Leaves", []))) {
                $event->cancel();
            }
        }
    }

    public function onBlockBreak(BlockBreakEvent $event): void {
        if (!$this->noDecayEnabled) {
            return;
        }

        $block = $event->getBlock();
        $world = $block->getPosition()->getWorld()->getDisplayName();

        if ($this->config->get("NoDecay-Leaves", true) && $this->isLeafBlock($block)) {
            if ($this->config->get("AllWorld-NoDecay-Leaves", true) || 
                in_array($world, $this->config->get("World-NoDecay-Leaves", []))) {
                $event->cancel();
            }
        }
    }

    private function isLeafBlock(Block $block): bool {
        $leaves = [
            VanillaBlocks::OAK_LEAVES()->getName(),
            VanillaBlocks::SPRUCE_LEAVES()->getName(),
            VanillaBlocks::BIRCH_LEAVES()->getName(),
            VanillaBlocks::JUNGLE_LEAVES()->getName(),
            VanillaBlocks::ACACIA_LEAVES()->getName(),
            VanillaBlocks::DARK_OAK_LEAVES()->getName(),
            VanillaBlocks::MANGROVE_LEAVES()->getName(),
            VanillaBlocks::CHERRY_LEAVES()->getName(),
            VanillaBlocks::AZALEA_LEAVES()->getName()
        ];

        return in_array($block->getName(), $leaves, true);
    }

    public function isNoDecayEnabled(): bool {
        return $this->noDecayEnabled;
    }
}
