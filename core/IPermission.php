<?php

interface IPermission{

	function checkPermission($actionName);
    function getRedirectPage($actionName);

}