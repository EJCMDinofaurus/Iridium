<?php

class GandAuth_Action
{
	private $permissionsLevels = array();
	private $redirectPage;

	function __construct($permissionsLevels, $redirectPage)
	{
		$this->permissionsLevels = $permissionsLevels;
        $this->redirectPage = $redirectPage;
	}

	public function addPermissionLevel($permissionLevel)
	{
		$this->permissionsLevels[] = $permissionLevel;
	}

	public function checkLevel($level)
	{
		foreach($this->permissionsLevels as $permissionLevel)
		{
			if($permissionLevel == $level)
				return true;
		}

		return false;
	}

	public function setRedirectPage($redirectPage)
	{
		$this->redirectPage = $redirectPage;
	}

	public function getRedirectPage()
	{
		return $this->redirectPage;
	}
}
