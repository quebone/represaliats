<?php
namespace Represaliats;

// bootstrap.php
use Doctrine\ORM\Tools\Setup;
use Doctrine\ORM\EntityManager;

if (!defined('BASEDIR')) define('BASEDIR', '');
if (!defined('ENTITIESDIR')) define('ENTITIESDIR', BASEDIR . 'service/entities/');

require_once BASEDIR.'vendor/autoload.php';
require_once BASEDIR.'DAO.php';

$paths = array(ENTITIESDIR);
$isDevMode = true;

// the connection configuration
define("LOCAL", true);
// local
if (defined("LOCAL")) {
	$dbParams = array(
			'driver'   => 'pdo_mysql',
			'user'     => 'carles',
			'password' => 'litus',
			'dbname'   => 'represaliats',
			'charset' => 'utf8',
	);
} else {
	// remote
	$dbParams = array(
			'driver'   => 'pdo_mysql',
			'user'     => 'carles',
			'password' => '19liTus71',
			'dbname'   => 'represaliats',
			'charset' => 'utf8',
	);
}

$config = Setup::createAnnotationMetadataConfiguration($paths, $isDevMode);
$entityManager = EntityManager::create($dbParams, $config);

$dao = DAO::getInstance();
$dao->setEM($entityManager);
