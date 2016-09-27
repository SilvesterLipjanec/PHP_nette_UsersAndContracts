<?php

namespace App\Presenters;

use Nette;
use Nette\Application\UI;


class SignPresenter extends Nette\Application\UI\Presenter
{
	/** @persistent */
	public $backlink = '';

	/**
	 * Sign-in form factory.
	 * @return Nette\Application\UI\Form
	 */
	protected function createComponentSignInForm()
	{
		$form = new UI\Form;
		$form->addText('username', 'login:')
			->setRequired('Zadajte svoj login.');

		$form->addPassword('password', 'Heslo:')
			->setRequired('Zadajte svoj heslo.');

		$form->addSubmit('send', 'Prilásiť');

		$form->onSuccess[] = [$this, 'signInFormSucceeded'];
		return $form;
	}
	
	public function signInFormSucceeded($form, $values)
	{
		try {
			$this->getUser()->login($values->username, $values->password);

		} catch (Nette\Security\AuthenticationException $e) {
			$form->addError($e->getMessage());
			return;
		}

		$this->restoreRequest($this->backlink);
		$this->redirect('Homepage:');
	}


	public function actionOut()
	{
		$this->getUser()->logout();
		$this->flashMessage('Boli ste odhlásený.');
		$this->redirect('in');
	}

}
