<?php
namespace Selenia\Themes\Gentelella\Config;

use Electro\Interfaces\ModuleInterface;
use Electro\Kernel\Lib\ModuleInfo;
use Electro\Kernel\Services\Bootstrapper;
use Electro\Plugins\Matisse\Config\MatisseSettings;
use Electro\ViewEngine\Config\ViewEngineSettings;
use Selenia\Themes\Gentelella\Components\SideBarMenu;
use const Electro\Kernel\Services\CONFIGURE;

class ThemeGentelellaModule implements ModuleInterface
{
  static function bootUp (Bootstrapper $bootstrapper, ModuleInfo $moduleInfo)
  {
    $bootstrapper->on (CONFIGURE, function (MatisseSettings $matisseSettings, ViewEngineSettings $viewEngineSettings)
    use ($moduleInfo) {
      $matisseSettings
        ->registerMacros ($moduleInfo)
        ->registerComponents ([
          'SideBarMenu' => SideBarMenu::class,
        ])
        // DO NOT IMPORT THE FOLLOWING NAMESPACE!
        ->registerControllersNamespace ($moduleInfo, \Selenia\Platform\Components::class, 'platform');
      $viewEngineSettings->registerViews ($moduleInfo);
    });
  }

}
