<?php
namespace Represaliats\Config;

if (!defined('BASEDIR')) define('BASEDIR', __DIR__);
define('PRESENTATIONDIR', BASEDIR.'presentation/');
define('TPLDIR', PRESENTATIONDIR.'template/');
define('MODELDIR', BASEDIR.'service/');
define('ENTITIESDIR', MODELDIR.'entities/');
define('FILESDIR', BASEDIR.'files/');
define('LANGUAGESDIR', BASEDIR.'languages/');

define('ENTITIESNS', 'Represaliats\\Service\\Entities\\');

define("LOGIN_ERROR", "Hi hagut un error en l'entrada de dades. Torna-ho a provar");

define('LOCALE', 0);
define('REMOTE', 1);

define("MINPASSWORDLENGTH", 6);
define("MAXPASSWORDLENGTH", 15);

define("DEFAULTVALUE", "--");