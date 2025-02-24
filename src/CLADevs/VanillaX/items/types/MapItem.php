<?php

namespace CLADevs\VanillaX\items\types;

use CLADevs\VanillaX\items\utils\NonAutomaticCallItemTrait;
use pocketmine\item\Item;
use pocketmine\item\ItemIds;
use pocketmine\math\Vector3;
use pocketmine\Player;

class MapItem extends Item implements NonAutomaticCallItemTrait{

    const MAP_EMPTY = ItemIds::EMPTY_MAP;
    const MAP_FILLED = ItemIds::FILLED_MAP;

    public function __construct(int $id){
        parent::__construct($id, 0, $id === self::MAP_EMPTY ? "Empty Map" : "Map");
    }

    public function onClickAir(Player $player, Vector3 $directionVector): bool{
//        if($this->isEmptyMap()){
            //TODO give filled map
//        }
        return true;
    }

    public function isEmptyMap(): bool{
        return $this->id === self::MAP_EMPTY;
    }
}