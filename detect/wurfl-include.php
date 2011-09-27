<?php

define("WURFL_DIR", dirname(__FILE__) . '/wurfl/WURFL/');
define("RESOURCES_DIR", dirname(__FILE__) . "/wurfl/resources/");

require_once WURFL_DIR . 'Application.php';

// Uncomment the follwoing lines to use the xml configuration file
//$wurflConfigFile = RESOURCES_DIR . 'wurfl-config.xml';
//$wurflConfig = new WURFL_Configuration_XmlConfig($wurflConfigFile);

$persistenceDir = RESOURCES_DIR . "storage/persistence";
$cacheDir = RESOURCES_DIR . "storage/cache";
$wurflConfig = new WURFL_Configuration_InMemoryConfig ();
$wurflConfig
        ->wurflFile(RESOURCES_DIR . "wurfl-2.2.zip")
        ->wurflPatch(RESOURCES_DIR . "web_browsers_patch.xml")
        ->persistence("file",array(
                                WURFL_Configuration_Config::DIR => $persistenceDir))
        ->cache("file", array(
                            WURFL_Configuration_Config::DIR => $cacheDir,
                            WURFL_Configuration_Config::EXPIRATION => 36000));


$wurflManagerFactory = new WURFL_WURFLManagerFactory($wurflConfig);

$wurflManager = $wurflManagerFactory->create(true);
$wurflInfo = $wurflManager->getWURFLInfo();

?>
