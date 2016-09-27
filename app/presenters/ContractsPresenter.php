<?php

namespace App\Presenters;

use App\Model;
use Nette;
use Nette\Application\UI\Form;
use Tracy\Debugger;
use Nette\Security;
Debugger::enable();

class ContractsPresenter extends Nette\Application\UI\Presenter
{
	private $database;

	public function __construct(Model\DatabaseRepository $database)
	{
		$this->database = $database;
	}
	public function renderEdit($number)
	{	
		
		$form = $this['contractForm'];
		if(!$form->isSubmitted()){
			$contract = $this->database->findContractByNumber($number);
			if(!$number){
				$this->error('Zaznam nebol najdeny');
			}
				$form->setDefaults($contract);
		}
	}
	public function renderDelete($number)
	{	

	}
	public function renderAdd()
	{
		$this['contractForm']['save']->caption = 'Pridať';
	}
	public function createComponentContractForm()
	{
		$form = new Form;

		$form->addInteger('number','Číslo zákazky:')
			->setRequired('Zadajte číslo zákazky');

		$form->addText("deadline", "Dátum:")
		    ->setRequired("Zadajte dátum zákazky")
		    ->setAttribute("placeholder", "rrrr-mm-dd");
		$form->addInteger('costs','Náklady:')
			->setRequired('Zadajte výšku nákladov(cost).');
		
		$form->addInteger('profit','Zisk:')
			->setRequired('Zadajte výšku zisku(profit).');


		$form->addSelect('user','Užívateľ',$this->database->getUserList($this->getUser()->getIdentity()->getData()))
			->setRequired('Zadajte užívateľa');

		$form->addSubmit('save', 'Uložiť')
			->setAttribute('class', 'default')	
			->onClick[] = [$this, 'contractFormSucceeded'];

		$form->addSubmit('cancel', 'Zrušiť')
			->setValidationScope([])
			->onClick[] = [$this, 'formCancelled'];

		$form->addProtection();
		return $form;
	}
	public function contractFormSucceeded($button)
	{ 
		$values = $button->getForm()->getValues();	
		$number = $this->getParameter('number');
		if($number){
			$this->database->findContractByNumber($number)->update($values);
			$this->flashMessage('Informácie o zákazke boli upravené.');
		}
		else{
			$this->database->insertContract($values);
			$this->flashMessage('Zákazka bola pridaná.');
		}
		$this->redirect('Homepage:');
	}
	
	public function createComponentDeleteForm()
	{
		$form = new Form;
		$form->addSubmit('delete', 'Odstrániť')
			->setAttribute('class', 'default')
			->onClick[] = [$this, 'deleteFormSucceeded'];

		$form->addSubmit('cancel', 'Zrušiť')
			->onClick[] = [$this, 'formCancelled'];
		return $form;
	}
	public function deleteFormSucceeded()
	{
		$delContractNum = $this->getParameter('number');
		$this->database->findContractByNumber($delContractNum)->delete();
		$this->flashMessage('Zákazka bola odstránená.');	
		$this->redirect('Homepage:default');
	}
	public function formCancelled()
	{
		$this->redirect('Homepage:default');
	}
}
