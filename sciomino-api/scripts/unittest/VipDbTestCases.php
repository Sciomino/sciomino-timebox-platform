<?php
require_once 'PHPUnit/Framework.php';
require_once 'PHPUnit/TextUI/TestRunner.php';

#
# the test suite
#
class VipDbTestSuite extends PHPUnit_Framework_TestSuite {

	#
	# This is the test wide set-up, it is only used for
	# - system variables
	# - config files
	# - database connections
	#
	protected function setUp() {

                global $_SERVER;
                global $XCOW_B;

                $_SERVER['DOCUMENT_ROOT'] = "../../htdocs";

                # load requirements
                require $_SERVER['DOCUMENT_ROOT']."/../data/etc/xcow_b.ini";
                require $_SERVER['DOCUMENT_ROOT']."/../data/model/utils.php";
                require $_SERVER['DOCUMENT_ROOT']."/../data/view/utils.php";

                # Open database connection
                $XCOW_B['mysql_link'] = mysql_connect($XCOW_B['mysql_host'], $XCOW_B['mysql_user'], $XCOW_B['mysql_pass']);
                mysql_select_db($XCOW_B['mysql_db'], $XCOW_B['mysql_link']);

	}

}

#
# the test cases
#
class VipDbTestCases extends PHPUnit_Framework_TestCase {

	protected static function traverseModule($dir, $suite) {

    		$testFiles = glob($dir.'/*.php');
		$suite->addTestFiles($testFiles);

	}

	protected static function traverseTests($base, $suite) {

		$modules = glob($base.'/*', GLOB_ONLYDIR);
		foreach ($modules as $module) {
			self::traverseModule($module, $suite);
		}
		
	}

	public static function suite() {

		#$suite = new PHPUnit_Framework_TestSuite('PHPUnit Framework');
		$suite = new vipDbTestSuite('PHPUnit Framework');
		self::traverseTests("../../data/test/vip_db", $suite);
		return $suite;
	}

}
