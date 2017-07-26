<?php
namespace Selenia\Themes\Gentelella\Config;

use Electro\Interfaces\KernelInterface;
use Electro\Interfaces\ModuleInterface;
use Electro\Kernel\Lib\ModuleInfo;
use Matisse\Config\MatisseSettings;
use Electro\Profiles\WebProfile;
use Electro\ViewEngine\Config\ViewEngineSettings;
use Selenia\Themes\Gentelella\Components\SideBarMenu;

class ThemeGentelellaModule implements ModuleInterface
{
  static function getCompatibleProfiles ()
  {
    return [WebProfile::class];
  }

  static function startUp (KernelInterface $kernel, ModuleInfo $moduleInfo)
  {
    $kernel->onConfigure (
      function (MatisseSettings $matisseSettings, ViewEngineSettings $viewEngineSettings)
      use ($moduleInfo) {
        $matisseSettings
          ->registerMacros ($moduleInfo)
          ->registerComponents ([
            'SideBarMenu' => SideBarMenu::class,
          ])
					->registerPresets([ThemeGentelellaPresets::class])
          // DO NOT IMPORT THE FOLLOWING NAMESPACE!
          ->registerControllersNamespace ($moduleInfo, \Selenia\Platform\Components::class, 'platform');
        $viewEngineSettings->registerViews ($moduleInfo);
      });
  }

}
