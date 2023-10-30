<?php

namespace refaltor\addfoodsmods\items;

use customiesdevs\customies\item\component\FoodComponent;
use customiesdevs\customies\item\component\UseAnimationComponent;
use customiesdevs\customies\item\CreativeInventoryInfo;
use customiesdevs\customies\item\ItemComponents;
use customiesdevs\customies\item\ItemComponentsTrait;
use pocketmine\item\Food;
use pocketmine\item\ItemIdentifier;
use pocketmine\utils\TextFormat;

class BeefSteak extends Food implements ItemComponents {
    use ItemComponentsTrait;

    public function __construct(ItemIdentifier $identifier)
    {
        $name = 'Beef Steak';

        $inventory = new CreativeInventoryInfo(
            CreativeInventoryInfo::CATEGORY_NATURE,
            CreativeInventoryInfo::GROUP_MISC_FOOD,
        );

        parent::__construct($identifier, $name);

        $this->initComponent('beef_steak',$inventory);
        $this->addComponent(new FoodComponent(false));
        $this->addComponent(new UseAnimationComponent(UseAnimationComponent::ANIMATION_EAT));
    }

    public function getFoodRestore(): int
    {
        return 10;
    }

    public function requiresHunger(): bool
    {
        return true;
    }

    public function getSaturationRestore(): float
    {
        return 20.0;
    }
}