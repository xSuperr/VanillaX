<?php

namespace CLADevs\VanillaX\commands\types;

use CLADevs\VanillaX\commands\Command;
use CLADevs\VanillaX\commands\CommandArgs;
use CLADevs\VanillaX\commands\CommandTargetSelector;
use CLADevs\VanillaX\utils\Utils;
use Exception;
use pocketmine\command\CommandSender;
use pocketmine\item\ItemFactory;
use pocketmine\item\ItemIds;
use pocketmine\network\mcpe\protocol\AvailableCommandsPacket;
use pocketmine\network\mcpe\protocol\types\PlayerPermissions;
use pocketmine\Player;
use pocketmine\utils\TextFormat;

class ClearCommand extends Command{

    public function __construct(){
        parent::__construct("clear", "Clears items from player inventory.");
        $this->setPermission("clear.command");
        $this->commandArg = new CommandArgs(CommandArgs::FLAG_NORMAL, PlayerPermissions::MEMBER);
        $this->commandArg->addParameter(0, "player", AvailableCommandsPacket::ARG_TYPE_TARGET);
        $this->commandArg->addParameter(0, "itemName", AvailableCommandsPacket::ARG_FLAG_ENUM | 0x9, true, "Item", json_decode(file_get_contents(Utils::getResourceFile("command_items.json"))));
        $this->commandArg->addParameter(0, "data", AvailableCommandsPacket::ARG_TYPE_INT);
        $this->commandArg->addParameter(0, "maxCount", AvailableCommandsPacket::ARG_TYPE_INT);
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args): void{
        if(!$this->testPermission($sender)) return;
        $player = [$sender];
        $itemName = null;
        $data = null;
        $maxCount = null;

        if(isset($args[0])){
            if(!$player = CommandTargetSelector::getFromString($sender, $args[0], true, true, true)) return;
            if(isset($args[1])){
                try{
                    $itemName = ItemFactory::fromStringSingle($args[1]);
                }catch (Exception $e){
                    $this->sendSyntaxError($sender, $args[1], "/$commandLabel", $args[1]);
                    return;
                }

                if(isset($args[2])){
                    if(!is_numeric($args[2])){
                        $this->sendSyntaxError($sender, $args[2], "/$commandLabel", $args[2], [$args[0], $args[1]]);
                        return;
                    }
                    $itemName->setDamage(intval($args[2]));

                    if(isset($args[3])){
                        if(!is_numeric($args[3]) || $args[3] < 1 || $args[3] > 100){
                            $this->sendSyntaxError($sender, $args[3], "/$commandLabel", $args[3], [$args[0], $args[1]]);
                            return;
                        }
                        $maxCount = intval($args[3]);
                    }
                }
            }
        }
        foreach($player as $p){
            if(!$p instanceof Player){
                continue;
            }
            if(count($p->getInventory()->getContents()) < 1 && count($p->getArmorInventory()->getContents()) < 1){
                $sender->sendMessage(TextFormat::RED . "Could not clear the inventory of " . $p->getName() . ", no items to remove");
                return;
            }
            $itemsCount = 0;
            if($itemName === null){
                foreach(array_merge($p->getInventory()->getContents(), $p->getArmorInventory()->getContents()) as $item){
                    $itemsCount += $item->getCount();
                }
                $p->getInventory()->clearAll();
                $p->getArmorInventory()->clearAll();
            }else{
                foreach($p->getInventory()->getContents() as $slot => $item){
                    if($item->equals($itemName)){
                        if($maxCount !== null && $item->getCount() > $maxCount){
                            continue;
                        }
                        $itemsCount += $item->getCount();
                        $p->getInventory()->setItem($slot, ItemFactory::get(ItemIds::AIR));
                    }
                }
                foreach($p->getArmorInventory()->getContents() as $slot => $item){
                    if($item->equals($itemName)){
                        if($maxCount !== null && $item->getCount() > $maxCount){
                            continue;
                        }
                        $itemsCount++;
                        $p->getArmorInventory()->setItem($slot, ItemFactory::get(ItemIds::AIR));
                    }
                }
                if($itemsCount < 1){
                    $sender->sendMessage(TextFormat::RED . "Could not clear the inventory of " . $p->getName() . ", no items to remove");
                    return;
                }
            }
            $sender->sendMessage("Cleared the inventory of " . $p->getName() . ", removing " . $itemsCount . " items");
        }
    }
}