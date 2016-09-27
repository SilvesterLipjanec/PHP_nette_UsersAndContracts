<?php
// source: /var/www/html/sandbox/app/presenters/templates/Homepage/default.latte

use Latte\Runtime as LR;

class Template4660ad7ca4 extends Latte\Runtime\Template
{
	public $blocks = [
		'content' => 'blockContent',
		'users' => 'blockUsers',
		'contracts' => 'blockContracts',
		'stats' => 'blockStats',
	];

	public $blockTypes = [
		'content' => 'html',
		'users' => 'html',
		'contracts' => 'html',
		'stats' => 'html',
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
		if (isset($this->params['data'])) trigger_error('Variable $data overwritten in foreach on line 12');
		if (isset($this->params['contract'])) trigger_error('Variable $contract overwritten in foreach on line 37');
		if (isset($this->params['stat'])) trigger_error('Variable $stat overwritten in foreach on line 61');
		Nette\Bridges\ApplicationLatte\UIRuntime::initialize($this, $this->parentName, $this->blocks);
		
	}


	function blockContent($_args)
	{
		extract($_args);
		$this->renderBlock('users', get_defined_vars());
		?>	<p><a href="<?php echo LR\Filters::escapeHtmlAttr($this->global->uiControl->link("add")) ?>">Pridať užívateľa</a></p>
	
	<table class="grid">
	<tr>
		<th>Login</th>
		<th>Password</th>
		<th>Boss</th>
		<th>&nbsp;</th>
	</tr>
<?php
		$iterations = 0;
		foreach ($users as $data) {
?>
		<tr>
			<td><?php echo LR\Filters::escapeHtmlText($data->login) /* line 14 */ ?></td>
			<td><?php echo LR\Filters::escapeHtmlText($data->password) /* line 15 */ ?></td>
			<td><?php echo LR\Filters::escapeHtmlText($data->boss) /* line 16 */ ?></td>		
			<td>
				<a href="<?php echo LR\Filters::escapeHtmlAttr($this->global->uiControl->link("edit", [$data->login])) ?>">Edit</a>
				<a href="<?php echo LR\Filters::escapeHtmlAttr($this->global->uiControl->link("delete", [$data->login])) ?>">Delete</a>
			</td>
		</tr>
<?php
			$iterations++;
		}
?>
	</table>

<?php
		$this->renderBlock('contracts', get_defined_vars());
		?>	<p><a href="<?php echo LR\Filters::escapeHtmlAttr($this->global->uiControl->link("Contracts:add")) ?>">Pridať zákazku</a></p>
	<table class="grid">
	<tr>
		<th>number</th>
		<th>user</th>
		<th>deadline</th>
		<th>costs</th>
		<th>profit</th>
		<th>&nbsp;</th>
	</tr>

<?php
		$iterations = 0;
		foreach ($contracts as $contract) {
?>
		<tr>
			<td><?php echo LR\Filters::escapeHtmlText($contract->number) /* line 39 */ ?></td>
			<td><?php echo LR\Filters::escapeHtmlText($contract->user) /* line 40 */ ?></td>
			<td><?php echo LR\Filters::escapeHtmlText($contract->deadline) /* line 41 */ ?></td>
			<td><?php echo LR\Filters::escapeHtmlText($contract->costs) /* line 42 */ ?></td>
			<td><?php echo LR\Filters::escapeHtmlText($contract->profit) /* line 43 */ ?></td>
			<td>
				<a href="<?php echo LR\Filters::escapeHtmlAttr($this->global->uiControl->link("Contracts:edit", [$contract->number])) ?>">Edit</a>
				<a href="<?php echo LR\Filters::escapeHtmlAttr($this->global->uiControl->link("Contracts:delete", [$contract->number])) ?>">Delete</a>
			</td>
		</tr>
<?php
			$iterations++;
		}
?>
	</table>

<?php
		$this->renderBlock('stats', get_defined_vars());
?>
	<table class="grid">
	<tr>
		<th>user</th>
		<th>count</th>
		<th>costs</th>
		<th>profit</th>
	</tr>

<?php
		$iterations = 0;
		foreach ($stats as $stat) {
?>
		<tr>
			<td><?php echo LR\Filters::escapeHtmlText($stat->user) /* line 63 */ ?></td>
			<td><?php echo LR\Filters::escapeHtmlText($stat->count) /* line 64 */ ?></td>
			<td><?php echo LR\Filters::escapeHtmlText($stat->costs) /* line 65 */ ?></td>
			<td><?php echo LR\Filters::escapeHtmlText($stat->profit) /* line 66 */ ?></td>
		</tr>
<?php
			$iterations++;
		}
?>
	</table>

 	<p><a href="<?php echo LR\Filters::escapeHtmlAttr($this->global->uiControl->link("Sign:out")) ?>">Odhlásiť sa</a></p>

<?php
	}


	function blockUsers($_args)
	{
		extract($_args);
?>	<h1>Užívatelia</h1>
<?php
	}


	function blockContracts($_args)
	{
		extract($_args);
?>	<h1>Zákazky</h1>
<?php
	}


	function blockStats($_args)
	{
		extract($_args);
?>	<h1>Štatistika</h1>
<?php
	}

}
