<?php
namespace CustomAuth;

/**
* Authentification interface
*/
interface IAuth {
	function auth(array $datas);
}