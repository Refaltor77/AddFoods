<?php

namespace refaltor\addfoodsmods;

use customiesdevs\customies\item\CustomiesItemFactory;
use pocketmine\plugin\PluginBase;
use refaltor\addfoodsmods\items\BeefSteak;
use refaltor\addfoodsmods\items\Cheese;
use refaltor\addfoodsmods\items\CheeseCake;
use refaltor\addfoodsmods\items\Donut;
use refaltor\addfoodsmods\items\Kinder;
use refaltor\addfoodsmods\items\Pepperoni;
use refaltor\addfoodsmods\items\Pizza;
use refaltor\addfoodsmods\items\Sandwich;
use refaltor\addfoodsmods\items\Tacos;
use refaltor\addfoodsmods\libs\Packs;

class Main extends PluginBase
{
    protected function onEnable(): void
    {
        CustomiesItemFactory::getInstance()->registerItem(BeefSteak::class, IdsCustom::BEEF_STEAK, "Beef Steak");
        CustomiesItemFactory::getInstance()->registerItem(Cheese::class, IdsCustom::CHEESE, "Cheese");
        CustomiesItemFactory::getInstance()->registerItem(Pizza::class, IdsCustom::PIZZA, "Pizza");
        CustomiesItemFactory::getInstance()->registerItem(Sandwich::class, IdsCustom::SANDWICH, "Sandwich");
        CustomiesItemFactory::getInstance()->registerItem(Tacos::class, IdsCustom::TACOS, "Tacos");
        CustomiesItemFactory::getInstance()->registerItem(CheeseCake::class, IdsCustom::CHEESECAKE, "Cheesecake");
        CustomiesItemFactory::getInstance()->registerItem(Donut::class, IdsCustom::DONUT, "Donut");
        CustomiesItemFactory::getInstance()->registerItem(Kinder::class, IdsCustom::KINDER, "Kinder");
        CustomiesItemFactory::getInstance()->registerItem(Pepperoni::class, IdsCustom::PEPPERONI, "Pepperoni");

        $pack = Packs::generatePackFromResources($this, "pack");
        Packs::unregisterResourcePack($pack);
        Packs::registerResourcePack($pack);
    }
}