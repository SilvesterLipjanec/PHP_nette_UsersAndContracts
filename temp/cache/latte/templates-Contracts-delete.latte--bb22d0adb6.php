<?php
// source: /var/www/html/sandbox/app/presenters/templates/Contracts/delete.latte

use Latte\Runtime as LR;

class Templatebb22d0adb6 extends Latte\Runtime\Template
{
	public $blocks = [
		'content' => 'blockContent',
		'title' => 'blockTitle',
	];

	public $blockTypes = [
		'content' => 'html',
		'title' => 'html',
	];


	function main()
	{
		extract($this->params);
		if ($this->getParentName()) return get_defined_vars();
		$this->renderBlock('content', get_defined_vars());
		return get_defined_vars();
	}


	function prepare()
	{
		extract($this->params);
		Nette\Bridges\ApplicationLatte\UIRuntime::initialize($this, $this->parentName, $this->blocks);
		
	}


	function blockContent($_args)
	{
		extract($_args);
?>

<?php
		$this->renderBlock('title', get_defined_vars());
?>


	<p>Naozaj chcete odstrániť danú zákazku?</p>
<?php
		/* line 7 */ $_tmp = $this->global->uiControl->getComponent("deleteForm");
		if ($_tmp instanceof Nette\Application\UI\IRenderable) $_tmp->redrawControl(NULL, FALSE);
		$_tmp->render();
?>

<?php
	}


	function blockTitle($_args)
	{
		extract($_args);
?><h1>Odstrániť zákazku</h1>
<?php
	}

}
