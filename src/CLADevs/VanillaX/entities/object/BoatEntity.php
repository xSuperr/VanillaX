<?php

namespace CLADevs\VanillaX\entities\object;

use CLADevs\VanillaX\entities\utils\EntityInteractable;
use CLADevs\VanillaX\entities\utils\EntityInteractResult;
use CLADevs\VanillaX\network\gamerules\GameRule;
use pocketmine\entity\Entity;
use pocketmine\item\ItemFactory;
use pocketmine\item\ItemIds;

class BoatEntity extends Entity implements EntityInteractable{

    public $width = 1.4;
    public $height = 0.455;
    protected $gravity = 0.05;

    const NETWORK_ID = self::BOAT;

    public function kill(): void{
        if(GameRule::getGameRuleValue(GameRule::DO_ENTITY_DROPS, $this->getLevel())){
            $this->getLevel()->dropItem($this, ItemFactory::get(ItemIds::BOAT)); //TODO boat types
        }
        parent::kill();
    }

    public function onInteract(EntityInteractResult $result): void{
        //TODO
    }
}