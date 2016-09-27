<?php

namespace App\Model;

use Nette;
use Tracy\Debugger;

Debugger::enable();

class DatabaseRepository{

	use Nette\SmartObject;

	private $database;
	private $names = array();

	public function __construct(Nette\Database\Context $database){
		
		$this->database = $database;
	}
	/* Nette\Database\Table\Selection */


	public function findAllUsers(array $user)
	{
		static $obj = [];
		static $count = 0;

		$data = []; 

		$login = $user['login'];
		array_push($this->names, $login);
		$subordinates = $this->database->table('users')->where('boss',$login)->fetchAll();
		if(!$count){
			$data[] = $this->database->table('users')->get($login);			
			$obj = (object) array_merge((array) $data, (array) $subordinates);
			$count++;
		}
		else{
			$obj = (object) array_merge((array)$subordinates,(array) $obj);
		}
		foreach ($subordinates as $subordinate) {
			$login = array('login' => $subordinate->login);
			$this->findAllUsers($login);

		}
		return $obj;		

	}

	public function findAllContracts()
	{	
		static $obj = [];
		foreach ($this->names as $user){
			$users = $this->database->table('contracts')->where('user',$user)->fetchAll();
			$obj = (object) array_merge((array) $users,(array) $obj);
		}
		return $obj;	
		
	}	
	public function getUserList($user)
	{	
		$this->findAllUsers($user);
		$userList = [];
		foreach ($this->names as $name) {
			$userList[$name] = $name;
		}

		return $userList;
	}
	public function findAllStats(){

		static $obj = [];
		foreach ($this->names as $user){
			$users = $this->database->table('stats')->where('user',$user)->fetchAll();
			$obj = (object) array_merge((array) $users,(array) $obj);
		}
		return $obj;
	}
	public function findUserByLogin($login)
	{
		return $this->database->table('users')->where('login',$login)->fetch();
	}
	public function findContractByNumber($number)
	{
		return $this->database->table('contracts')->where('number',$number)->fetch();
	}
	public function insertUser($values)
	{
		return $this->database->table('users')->insert($values);
	}
	public function insertContract($values)
	{
		return $this->database->table('contracts')->insert($values);
	}


}

