<?php

namespace App\Presenters;

use App\Model;
use Nette;
use Nette\Application\UI\Form;


class BasePresenter	 extends Nette\Application\UI\Presenter
{


	public function __construct(Model\DatabaseRepository $database)
	{
		
	}
}