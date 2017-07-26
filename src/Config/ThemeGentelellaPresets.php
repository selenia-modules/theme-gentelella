<?php
namespace Selenia\Themes\Gentelella\Config;

use Electro\Plugins\MatisseComponents\Checkbox;
use Electro\Plugins\MatisseComponents\RadioButton;

class ThemeGentelellaPresets
{
	function Checkbox (Checkbox $checkbox)
	{
		$prop                      = $checkbox->props;
		$prop->beforeLabelTemplate = "<i></i>";
	}

	function RadioButton (RadioButton $radioButton)
	{
		$prop                      = $radioButton->props;
		$prop->beforeLabelTemplate = "<i></i>";
	}
}
