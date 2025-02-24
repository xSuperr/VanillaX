<?php

namespace CLADevs\VanillaX\blocks\types;

use CLADevs\VanillaX\blocks\BlockIdentifiers;
use pocketmine\block\BlockFactory;
use pocketmine\block\Magma;
use pocketmine\block\Water;

class MagmaBlock extends Magma{

    public function onNearbyBlockChange(): void{
        //$this->level->scheduleDelayedBlockUpdate($this, 20);
    }

    public function onRandomTick(): void{
        $pos = $this->add(0, 1);

        if($this->getLevel()->getBlock($pos) instanceof Water){
            //TODO this sets but after a server restart or any block update this block turns into a 'White Stained Clay'
            $this->getLevel()->setBlock($pos, BlockFactory::get(BlockIdentifiers::BUBBLE_COLUMN));
        }
       $this->level->scheduleDelayedBlockUpdate($this, 20);
    }

    public function onScheduledUpdate(): void{
        $this->onRandomTick();
    }

    public function ticksRandomly(): bool{
        return false;
    }
}