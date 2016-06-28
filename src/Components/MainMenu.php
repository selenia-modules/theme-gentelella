<?php
namespace Selenia\Themes\Gentelella\Components;

use Electro\Interfaces\Navigation\NavigationLinkInterface;
use Electro\Plugins\Matisse\Components\Base\HtmlComponent;
use Electro\Plugins\Matisse\Components\Internal\Metadata;
use Electro\Plugins\Matisse\Properties\Base\HtmlComponentProperties;
use Electro\Plugins\Matisse\Properties\TypeSystem\type;

class MainMenuProperties extends HtmlComponentProperties
{
  /**
   * @var int
   */
  public $depth = 99;
  /**
   * @var bool
   */
  public $excludeRoot = false;
  /**
   * @var bool
   */
  public $excludeDividers = true;
  /**
   * @var string
   */
  public $expandIcon = '';
  /**
   * @var NavigationLinkInterface[]|\Traversable
   */
  public $menu = type::data;
  /**
   * @var Metadata
   */
  public $sectionTemplate = type::content;
}

class MainMenu extends HtmlComponent
{
  const propertiesClass = MainMenuProperties::class;

  /** @var MainMenuProperties */
  public $props;

  protected $containerTag = 'ul';
  protected $depthClass   = ['', 'child_menu', 'child_menu', 'child_menu', 'child_menu'];

  protected function init ()
  {
    parent::init ();
//    $this->context->getAssetsService ()->addStylesheet ('modules/electro-modules/matisse-components/css/metisMenu.css');
//    $this->context->getAssetsService ()->addScript ('modules/electro-modules/matisse-components/js/metisMenu.js');
  }

  protected function render ()
  {
    $prop = $this->props;

    $this->beginContent ();

    $xi = $prop->expandIcon;
    if ($prop->menu instanceof NavigationLinkInterface)
      $links = $prop->excludeRoot ? $prop->menu : [$prop->menu];
    else $links = $prop->menu;
    if (!$links) return;

    echo html (
      map ($links, function ($link) use ($xi) {
        /** @var NavigationLinkInterface $link */
        if (!$link) return '';
        if (is_array ($link))
          $link = $link[0];
        // Exclude hidden links and menu separators.
        if (!$link->isActuallyVisible () || ($link->isGroup () && $link->title () == '-')) return null;
        $children = $link->getMenu ();
        $children->rewind ();
        $active  = $link->isActive () ? '.active' : '';
        $sub     = $children->valid () ? '.sub' : '';
        $current = $link->isSelected () ? '.current' : '';
        $url     = $link->isGroup () && !isset ($link->defaultURI) ? null : $link->url ();
        $header  = $link->isGroup () && $link->title () != '-' ? '.header' : '';
        return [
          h ("li$active$sub$current$header", [
            h ("a$active", [
              'href' => $url,
            ], [
              when ($link->icon (), h ('i', ['class' => $link->icon ()])),
              $link->title (),
              when (isset($xi) && $sub, h ("span.$xi")),
            ]),
            when ($sub, $this->renderMenuItem ($children, $xi, false /*$link->matches*/)),
          ]),
        ];
      })
    );
  }

  private function renderMenuItem (\Iterator $links, $xi, $parentIsActive, $depth = 1)
  {
    $links->rewind ();
    if (!$links->valid () || $depth >= $this->props->depth)
      return null;
    return h ('ul.nav.collapse.' . $this->depthClass[$depth],
      map ($links, function (NavigationLinkInterface $link) use ($xi, $depth, $parentIsActive) {
        if (!$link->isActuallyVisible () || ($link->isGroup () && $link->title () == '-' && $this->props->excludeDividers)) return null;
        $children = $link->getMenu ();
        $children->rewind ();
        $active        = $link->isActive () ? '.active' : '';
        $sub           = $children->valid () ? '.sub' : '';
        $current       = $link->isSelected () ? '.current' : '';
        $disabled      = !$link->isActuallyEnabled ();
        $url           =
          $disabled || ($link->isGroup () && !isset ($link->defaultURI)) ? null : $link->url ();
        $disabledClass = $disabled ? '.disabled' : '';
        return [
          h ("li$active$sub$current", [
            h ("a$active$disabledClass", [
              'href' => $url,
            ], [
              when ($link->icon (), h ('i', ['class' => $link->icon ()])),
              $link->title (),
              when (isset($xi) && $sub, h ("span.$xi")),
            ]),
            when ($sub, $this->renderMenuItem ($children, $xi, false /*$link->matches*/, $depth + 1)),
          ]),
        ];
      })
    );
  }

}
