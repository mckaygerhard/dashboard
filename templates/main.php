<?php
\OCP\Util::addScript('dashboard', 'script');
\OCP\Util::addStyle('dashboard', 'widgets');
\OCP\Util::addStyle('dashboard', 'control');

$widgets = $_['enabledWidgets'];
$widgetsJson = OC_JSON::encode($widgets);
?>

<div id="app">
	<div id="app-content" data-enabledwidgets='<?php print_unescaped($widgetsJson); ?>'>
		<?php print_unescaped($this->inc('part.content'), $widgets); ?>
		<?php print_unescaped($this->inc('part.control')); ?>
	</div>
</div>
