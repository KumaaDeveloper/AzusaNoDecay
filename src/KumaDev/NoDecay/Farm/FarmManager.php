<?php

namespace KumaDev\NoDecay\Farm;

use pocketmine\event\Listener;
use pocketmine\event\block\BlockBreakEvent;
use pocketmine\event\entity\EntityTrampleFarmlandEvent;
use pocketmine\block\Block;
use pocketmine\block\Farmland;
use pocketmine\block\Wheat;
use pocketmine\block\Carrot;
use pocketmine\block\Potato;
use pocketmine\block\Beetroot;
use pocketmine\block\PumpkinStem;
use pocketmine\block\MelonStem;
use pocketmine\plugin\PluginBase;
use KumaDev\NoDecay\Main;

class FarmManager implements Listener {

    private Main $plugin;

    public function __construct(Main $plugin) {
        $this->plugin = $plugin;
    }

    public function onEntityTrampleFarmland(EntityTrampleFarmlandEvent $event): void {
        if (!$this->plugin->isNoDecayEnabled()) {
            return;
        }

        $block = $event->getBlock();
        $world = $block->getPosition()->getWorld()->getDisplayName();

        if ($this->plugin->getConfig()->get("NoDecay-Farmland", true)) {
            if ($this->plugin->getConfig()->get("AllWorld-NoDecay-Farmland", true) || 
                in_array($world, $this->plugin->getConfig()->get("World-NoDecay-Farmland", []))) {
                $event->cancel();
            }
        }
    }

    public function onBlockBreak(BlockBreakEvent $event): void {
        if (!$this->plugin->isNoDecayEnabled()) {
            return;
        }

        $block = $event->getBlock();
        $world = $block->getPosition()->getWorld()->getDisplayName();

        if ($this->plugin->getConfig()->get("NoDecay-Farmland", true) && $block instanceof Farmland) {
            if ($this->plugin->getConfig()->get("AllWorld-NoDecay-Farmland", true) || 
                in_array($world, $this->plugin->getConfig()->get("World-NoDecay-Farmland", []))) {
                $event->cancel();
            }
        }

        if ($this->plugin->getConfig()->get("NoDecay-Crops", true) && $this->isCropBlock($block)) {
            if ($this->plugin->getConfig()->get("AllWorld-NoDecay-Crops", true) || 
                in_array($world, $this->plugin->getConfig()->get("World-NoDecay-Crops", []))) {
                $event->cancel();
            }
        }

        if ($this->plugin->getConfig()->get("NoDecay-Seed", true) && $this->isFarmSeedBlock($block)) {
            if ($this->plugin->getConfig()->get("AllWorld-NoDecay-Seed", true) || 
                in_array($world, $this->plugin->getConfig()->get("World-NoDecay-Seed", []))) {
                $event->cancel();
            }
        }
    }

    private function isCropBlock(Block $block): bool {
        return $block instanceof Wheat || 
               $block instanceof Carrot || 
               $block instanceof Potato || 
               $block instanceof Beetroot;
    }

    private function isFarmSeedBlock(Block $block): bool {
        return $block instanceof PumpkinStem || 
               $block instanceof MelonStem;
    }
}
