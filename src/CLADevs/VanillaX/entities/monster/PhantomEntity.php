<?php

namespace CLADevs\VanillaX\entities\monster;

use CLADevs\VanillaX\entities\VanillaEntity;

class PhantomEntity extends VanillaEntity{

    const NETWORK_ID = self::PHANTOM;

    public $width = 0.9;
    public $height = 0.5;

    protected function initEntity(): void{
        parent::initEntity();
        $this->setMaxHealth(20);
    }

    public function getName(): string{
        return "Phantom";
    }
}