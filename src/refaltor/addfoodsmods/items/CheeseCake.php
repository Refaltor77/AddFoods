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

class CheeseCake extends Food implements ItemComponents {
    use ItemComponentsTrait;

    public function __construct(ItemIdentifier $identifier)
    {
        $name = 'Cheesecake';

        $inventory = new CreativeInventoryInfo(
            CreativeInventoryInfo::CATEGORY_NATURE,
            CreativeInventoryInfo::GROUP_MISC_FOOD,
        );

        parent::__construct($identifier, $name);

        $this->initComponent('cheesecake',$inventory);
        $this->addComponent(new FoodComponent(false));
        $this->addComponent(new UseAnimationComponent(UseAnimationComponent::ANIMATION_EAT));
    }

    public function getFoodRestore(): int
    {
        return 14;
    }

    public function requiresHunger(): bool
    {
        return true;
    }

    public function getSaturationRestore(): float
    {
        return 13.0;
    }
}