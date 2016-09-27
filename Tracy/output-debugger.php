<?php

require __DIR__ . '/../../Nette/loader.php';

Tracy\OutputDebugger::enable();


function head()
{
	echo '<!DOCTYPE html><link rel="stylesheet" href="assets/style.css">';
}


head();
echo '<h1>Output Debugger demo</h1>';
