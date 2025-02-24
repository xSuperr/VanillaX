<?php

namespace CLADevs\VanillaX\items\types;

use CLADevs\VanillaX\items\utils\NonAutomaticCallItemTrait;
use pocketmine\item\Item;

class MusicDiscItem extends Item implements NonAutomaticCallItemTrait{

    const RECORD_PIGSTEP = 759;

    private ?int $soundId;

    public function __construct(int $id, int $meta = 0, string $name = "Unknown", ?int $soundId = null){
        parent::__construct($id, $meta, $name);
        $this->soundId = $soundId;
    }

    public function getSoundId(): ?int{
        return $this->soundId;
    }

    public static function getRecordName(int $id, bool $returnNull = false): ?string{
        switch($id){
            case self::RECORD_CAT:
                $name = "cat";
                break;
            case self::RECORD_BLOCKS:
                $name = "blocks";
                break;
            case self::RECORD_CHIRP:
                $name = "chirp";
                break;
            case self::RECORD_FAR:
                $name = "far";
                break;
            case self::RECORD_MALL:
                $name = "mall";
                break;
            case self::RECORD_MELLOHI:
                $name = "mellohi";
                break;
            case self::RECORD_STAL:
                $name = "stal";
                break;
            case self::RECORD_STRAD:
                $name = "strad";
                break;
            case self::RECORD_WARD:
                $name = "ward";
                break;
            case self::RECORD_11:
                $name = "11";
                break;
            case self::RECORD_WAIT:
                $name = "wait";
                break;
            case self::RECORD_PIGSTEP:
                return "Lena Raine - Pigstep";
            default:
                $name = $returnNull ? null : "13";
                break;
        }
        return $name !== null ? "C418 - " . $name : null;
    }

    public function getMaxStackSize(): int{
        return 1;
    }
}