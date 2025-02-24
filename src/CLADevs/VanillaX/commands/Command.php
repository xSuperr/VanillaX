<?php

namespace CLADevs\VanillaX\commands;

use pocketmine\command\CommandSender;
use pocketmine\utils\TextFormat;

abstract class Command extends \pocketmine\command\Command{

    protected ?CommandArgs $commandArg = null;

    public function canRegister(): bool{
        return true;
    }

    public function getCommandArg(): ?CommandArgs{
        return $this->commandArg;
    }

    public function sendSyntaxError(CommandSender $sender, string $name, string $at, string $extra = "", array $args = []): void{
        $argsList = count($args) >= 1 ? " " . implode(" ", $args) : "";
        $sender->sendMessage(TextFormat::RED . "Syntax error: Unexpected \"$name\": at \"$at>>$extra<<$argsList\"");
    }
}