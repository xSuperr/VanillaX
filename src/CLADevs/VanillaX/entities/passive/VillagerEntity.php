<?php

namespace CLADevs\VanillaX\entities\passive;

use CLADevs\VanillaX\entities\utils\EntityInteractable;
use CLADevs\VanillaX\entities\utils\EntityInteractResult;
use CLADevs\VanillaX\entities\utils\trade\VillagerOffer;
use CLADevs\VanillaX\entities\utils\trade\VillagerProfession;
use CLADevs\VanillaX\entities\utils\trade\VillagerTradeNBTStream;
use CLADevs\VanillaX\entities\VanillaEntity;
use CLADevs\VanillaX\inventories\types\TradeInventory;
use CLADevs\VanillaX\VanillaX;
use pocketmine\item\ItemIds;
use pocketmine\level\Level;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\network\mcpe\protocol\types\WindowTypes;
use pocketmine\network\mcpe\protocol\UpdateTradePacket;

class VillagerEntity extends VanillaEntity implements EntityInteractable{

    const TAG_PROFESSION = "Profession";

    const NETWORK_ID = self::VILLAGER;

    public $width = 0.6;
    public $height = 1.9;

    private ?VillagerProfession $profession = null;
    private TradeInventory $inventory;

    private bool $canHaveGUI = false;

    public function __construct(Level $level, CompoundTag $nbt){
        parent::__construct($level, $nbt);
        if($nbt->hasTag(self::TAG_PROFESSION)){
            $this->profession = VanillaX::getInstance()->getEntityManager()->getVillagerProfessionFor($nbt->getInt(self::TAG_PROFESSION));
        }
        if($this->profession === null){
            $this->profession = VanillaX::getInstance()->getEntityManager()->getVillagerProfessionFor(VillagerProfession::UNEMPLOYED);
        }
        if($this->profession->getId() > 1){
            $this->canHaveGUI = true;
        }
        $this->inventory = new TradeInventory($this);
    }

    protected function initEntity(): void{
        parent::initEntity();
        $this->setMaxHealth(20);
    }

    public function getName(): string{
        return "Villager";
    }

    public function saveNBT(): void{
        $this->namedtag->setInt(self::TAG_PROFESSION, $this->profession->getId());
        parent::saveNBT();
    }

    public function onInteract(EntityInteractResult $result): void{
        if($this->canHaveGUI){
            $player = $result->getPlayer();
            $windowId = $player->addWindow($this->inventory, WindowTypes::TRADING);

            $testOffer = new VillagerOffer(100, 1, 10, 10, ItemIds::EMERALD, null, ItemIds::DIAMOND);
            $testOffer2 = new VillagerOffer(300, 1, 2, 0, ItemIds::DIAMOND, ItemIds::REDSTONE_DUST, ItemIds::SADDLE);
            $stream = new VillagerTradeNBTStream();
            $stream->addOffer([$testOffer, $testOffer2]);
            $stream->initialize();

            $pk = new UpdateTradePacket();
            $pk->windowId = $windowId;
            $pk->windowType = WindowTypes::TRADING;
            $pk->windowSlotCount = 0;
            $pk->tradeTier = 1;
            $pk->traderEid = $this->getId();
            $pk->playerEid = $player->getId();
            $pk->displayName = $this->profession->getName();
            $pk->isV2Trading = true;
            $pk->isWilling = false;
            $pk->offers = $stream->getBuffer();
            $player->dataPacket($pk);
        }
    }
}