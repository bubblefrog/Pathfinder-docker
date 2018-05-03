<?php
$composerAutoloader = 'vendor/autoload.php';
$loader = require($composerAutoloader);
$f3 = require_once('app/lib/base.php');

require_once("app/main/controller/controller.php");
require_once("app/main/controller/setup.php");
require_once("app/main/db/database.php");
require_once("app/main/lib/config.php");
require_once("app/main/controller/logcontroller.php");
require_once("app/main/exception/baseexception.php");
require_once("app/main/exception/pathfinderexception.php");
var_dump(get_included_files());

$f3->config('app/config.ini',true);
lib\Config::instance($f3);


use Controller\Setup;
class Bootstrap extends Setup{
        function setupDBs(){
                print_r("DB instance\n");
                $this->dbLib = DB\Database::instance();
                print_r("Bootstrap PF\n");
                $r=$this->bootstrapDB("UNIVERSE");
                print_r($r);
                print_r("Boostrap UNIVERSE\n");
                $r=$this->bootstrapDB("PF");
                print_r($r);
                //print_r("Fix keys\n");
                //$r=$this->checkDatabase($f3,true);
                print_r($r);
                $this->setupSystemJumpTable();

                $r=$this->importTable("WormholeModel");
                print_r($r);
                $r=$this->importTable("SystemWormholeModel");
                print_r($r);
                $r=$this->importTable("ConstellationWormholeModel");
                print_r($r);
        }
}
$s = new Bootstrap;
$s->setupDBs();
?>