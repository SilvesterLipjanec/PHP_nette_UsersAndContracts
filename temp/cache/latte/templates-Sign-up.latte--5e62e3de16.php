<?php
// source: /var/www/html/sandbox/app/presenters/templates/Sign/up.latte

use Latte\Runtime as LR;

class Template5e62e3de16 extends Latte\Runtime\Template
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
		$this->renderBlock('title', get_defined_vars());
?>

<?php
		/* line 4 */ $_tmp = $this->global->uiControl->getComponent("signUpForm");
		if ($_tmp instanceof Nette\Application\UI\IRenderable) $_tmp->redrawControl(NULL, FALSE);
		$_tmp->render();
?>

<p><a href="<?php echo LR\Filters::escapeHtmlAttr($this->global->uiControl->link("in")) ?>">Already have an account? Log in.</a></p>
<?php
	}


	function blockTitle($_args)
	{
		extract($_args);
?><h1>Sign Up</h1>
<?php
	}

}
