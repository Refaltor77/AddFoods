<?php

namespace refaltor\addfoodsmods\libs;

use pocketmine\plugin\PluginBase;
use pocketmine\resourcepacks\ResourcePack;
use pocketmine\resourcepacks\ZippedResourcePack;
use pocketmine\Server;
use pocketmine\utils\Filesystem;
use pocketmine\utils\Utils;
use Symfony\Component\Filesystem\Path;

final class Packs {

    # THX https://github.com/jasonw4331
    # THX https://github.com/jasonw4331/libCustomPack/blob/master/src/libCustomPack.php

    final public static function generatePackFromResources(PluginBase $plugin, ?string $packFolderName = null) : ZippedResourcePack{
        $packFolderName ??= $plugin->getName() . ' Pack';
        $packFolderRegex = str_replace(' ', '\h', $packFolderName);

        $zip = new \ZipArchive();
        $outputFilePath = Path::join($plugin->getDataFolder(), $plugin->getName() . '.mcpack');
        $zip->open($outputFilePath, \ZipArchive::CREATE | \ZipArchive::OVERWRITE);
        foreach($plugin->getResources() as $resource){
            if($resource->isFile() && str_contains($resource->getPathname(), $packFolderName)){
                $found = preg_replace("/.*[\/\\\\]{$packFolderRegex}[\/\\\\].*/U", '', $resource->getPathname());
                if(!is_string($found)){
                    throw new \InvalidArgumentException("Invalid path: $packFolderName");
                }
                $relativePath = Path::normalize($found);
                $plugin->saveResource(Path::join($packFolderName, $relativePath), false);
                $zip->addFile(Path::join($plugin->getDataFolder(), $packFolderName, $relativePath), $relativePath);
            }
        }
        $zip->close();
        Filesystem::recursiveUnlink(Path::join($plugin->getDataFolder(), $packFolderName)); // clean up

        $newFileName = (new ZippedResourcePack($outputFilePath))->getPackName() . '.mcpack';
        assert(rename($outputFilePath, $newFileName));

        return new ZippedResourcePack(Path::join($plugin->getDataFolder(), $newFileName));
    }

    final public static function generatePackFromPath(PluginBase $plugin, string $inputFilePath, ?string $packFolderName = null) : ZippedResourcePack{
        $packFolderName ??= $plugin->getName() . ' Pack';
        $packFolderRegex = str_replace(' ', '\h', $packFolderName);

        $zip = new \ZipArchive();
        $outputFilePath = Path::join($plugin->getDataFolder(), $plugin->getName() . '.mcpack');
        $zip->open($outputFilePath, \ZipArchive::CREATE | \ZipArchive::OVERWRITE);
        /**
         * @var \SplFileInfo $resource
         */
        foreach(new \RecursiveDirectoryIterator($inputFilePath,
            \FilesystemIterator::KEY_AS_PATHNAME | \FilesystemIterator::CURRENT_AS_FILEINFO | \FilesystemIterator::SKIP_DOTS) as
                $resource
        ){
            if($resource->isFile() && str_contains($resource->getPathname(), $packFolderName)){
                $found = preg_replace("/.*[\/\\\\]{$packFolderRegex}[\/\\\\].*/U", '', $resource->getPathname());
                if(!is_string($found)){
                    throw new \InvalidArgumentException("Invalid path: $packFolderName");
                }
                $relativePath = Path::normalize($found);
                copy(Path::join($packFolderName, $relativePath), Path::join($plugin->getDataFolder(), $packFolderName, $relativePath));
                $zip->addFile(Path::join($plugin->getDataFolder(), $packFolderName, $relativePath), $relativePath);
            }
        }
        $zip->close();
        Filesystem::recursiveUnlink(Path::join($plugin->getDataFolder(), $packFolderName)); // clean up

        $newFileName = (new ZippedResourcePack($outputFilePath))->getPackName() . '.mcpack';
        Utils::assumeNotFalse(rename($outputFilePath, $newFileName)); // TODO: does the ZippedResourcePack get freed before this happens?

        return new ZippedResourcePack($newFileName);
    }

    final public static function registerResourcePack(ResourcePack $resourcePack, ?string $encryptionKey = null) : void{
        $manager = Server::getInstance()->getResourcePackManager();
        $manager->setResourceStack($manager->getResourceStack() + [$resourcePack]);
        $manager->setPackEncryptionKey($resourcePack->getPackId(), $encryptionKey);
    }

    final public static function unregisterResourcePack(ResourcePack $resourcePack) : void{
        $manager = Server::getInstance()->getResourcePackManager();
        $stack = $manager->getResourceStack();
        $key = array_search($resourcePack, $stack, true);
        if($key !== false){
            unset($stack[$key]);
            $manager->setResourceStack($stack);
            $manager->setPackEncryptionKey($resourcePack->getPackId(), null);
        }
    }
}