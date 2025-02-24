<?php

namespace CLADevs\VanillaX\blocks\tiles;

use CLADevs\VanillaX\inventories\types\DispenserInventory;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\tile\Container;
use pocketmine\tile\ContainerTrait;
use pocketmine\tile\Spawnable;

class DispenserTile extends Spawnable implements Container{
    use ContainerTrait;

    private DispenserInventory $inventory;

    public function getName(): string{
        return "Dispenser";
    }

    public function getInventory(): DispenserInventory{
        return $this->inventory;
    }

    public function getRealInventory(): DispenserInventory{
        return $this->inventory;
    }

    protected function readSaveData(CompoundTag $nbt): void{
        $this->inventory = new DispenserInventory($this);
        $this->loadItems($nbt);
    }

    protected function writeSaveData(CompoundTag $nbt): void{
        $this->saveItems($nbt);
    }

    protected function addAdditionalSpawnData(CompoundTag $nbt): void{
    }
}