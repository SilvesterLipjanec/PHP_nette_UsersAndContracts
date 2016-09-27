<?php

namespace App\Presenters;

use App\Model;
use Nette;
use Nette\Application\UI\Form;
use Tracy\Debugger;
use Nette\Security;
Debugger::enable();

class HomepagePresenter extends Nette\Application\UI\Presenter
{
	private $users;
	private $contracts;
	private $stats;
	private $database;

	public function __construct(Model\DatabaseRepository $database)
	{
		$this->database = $database;
	}
	public function startup()
	{
		Parent::startup();
		if(!$this->getUser()->isLoggedIn())
		{
			$this->redirect('Sign:in');
		}

	}
	public function renderDefault()
	{
		$this->template->users = $this->database->findAllUsers($this->getUser()->getIdentity()->getData());
		$this->template->contracts = $this->database->findAllContracts();
		$this->template->stats = $this->database->findAllStats();
	}
	
	public function renderEdit($login)
	{		
		
		$form = $this['userEditForm'];
		if(!$form->isSubmitted()){
			$user = $this->database->findUserByLogin($login);
			if(!$user){
				$this->error('Zaznam nebol najdeny');
			}
			$form->setDefaults($user);
		}
		
	}
	public function renderDelete($login){

	}
	public function renderAdd()
	{
		$this['userForm']['save']->caption = 'Pridať';
	}
	public function getDefaults($user)
	{
		$defUser = array("login" => $user->login ,
						 "password" => $user->password);
		return $defUser;
	}
	protected function createComponentUserForm(){

		$form = new Form;
		$form->addText('login', 'Meno:')
			->setRequired('Zadajte pouzivatelske meno.');
		
		$form->addPassword('password', 'Heslo:')
			->setRequired('Zadajte heslo.');
		
		$form->addSelect('boss','Nadriadený:',$this->database->getUserList($this->getUser()->getIdentity()->getData()));

		$form->addSubmit('save', 'Uložiť')
			->setAttribute('class', 'default')
			->onClick[] = [$this, 'userFormSucceeded'];

		$form->addSubmit('cancel', 'Zrušiť')
			->setValidationScope([])
			->onClick[] = [$this, 'formCancelled'];

		$form->addProtection();
		return $form;
	}
	protected function createComponentUserEditForm(){

		$form = new Form;
		$form->addText('login', 'Meno:')
			->setRequired('Zadajte pouzivatelske meno.');
		
		$form->addPassword('password', 'Heslo:')
			->setRequired('Zadajte heslo.');
		
		$userData = $this->database->findUserByLogin($this->getParameter('login'));
		
		if($userData['boss'] == NULL){
			$users = [];
		}
		else{
			$users = $this->database->getUserList($this->getUser()->getIdentity()->getData());		
			$loginLoggedUser = $this->getParameter('login');
			unset($users[$loginLoggedUser]);	
		}	
			
		$form->addSelect('boss','Nadriadený:',$users);

		$form->addSubmit('save', 'Uložiť')
			->setAttribute('class', 'default')
			->onClick[] = [$this, 'userFormSucceeded'];

		$form->addSubmit('cancel', 'Zrušiť')
			->setValidationScope([])
			->onClick[] = [$this, 'formCancelled'];

		$form->addProtection();
		return $form;
	}
	public function userFormSucceeded($button)
	{ 
		$values = $button->getForm()->getValues();
		$login = $this->getParameter('login');
		if($login){
			$this->database->findUserByLogin($login)->update($values);
			$this->flashMessage('Informácie o užívateľovi boli upravené.');
		}else{
			$this->database->insertUser($values);
			$this->flashMessage('Užívateľ bol pridaný.');
		}
		$this->redirect('Homepage:');

	}
	public function formCancelled()
	{
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
		$count = 0;
		$deletedUserLogin = $this->getParameter('login');
		$arrLogin = array('login' => $deletedUserLogin);	
		$users = $this->database->findAllUsers($arrLogin);		
		foreach ($users as $user) {
			if($user->boss == $deletedUserLogin){
				$this->flashMessage('Užívateľ je niekomu vedúcim a nemožno ho odstrániť');
				$this->redirect('Homepage:default');
				$count++;
				break;
			}
		}	
		if(!$count){	
			$this->database->findUserByLogin($deletedUserLogin)->delete();
			$this->flashMessage('Užívateľ bol odstránený.');	
			$this->redirect('Homepage:default');
		}
	}


}