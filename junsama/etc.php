<?php
/*
class A {
	function ex() {
		echo "aaa";
	}
}

class B extends A {
	function ex() {
		parent::ex();
	}
}

$b = new B;
$b->ex();
*/
/*
class aaa {
	var $aaa_mv = "상수";
	
	function aaa1() {
		echo $this->aaa_mv;
	}
}

$rv = new aaa;
$rv->aaa_mv = "상수가 아닌 값";
$rv->aaa1();
*/
/*
class Test {
	public $prop1 = 1;
	protected $prop2 =2;
	private $prop3 = 3;
	var $prop4 = 4;
	
	public function pub_method1() {
		echo $this->prop3;
	}
	
	protected function pro_method2() {
	}
	
	private function pri_method2() {
	}
}

$obj = new Test;
$obj->pub_method2();
*/
/*
class Test {
	const one = 1;
	const two = 2;
	const three = 3;
	const fore = 4;
	
	public function pub_method() {
		print self::one;
	}
}

$obj = new Test;
$obj->pub_method();
*/

class test {
	public $var1 = 10;
	public static $Svar1 = 10;
	
	function var_plus() {
		$var1 += 10;
	}
	
	function Svar_plus() {
		self::$Svar1 += 10;
	}
	
	function print_Sva() {
		print self::$Svar1;
	}
}	

$obj = new test;
$obj->var_plus();
echo "<hr>";
$obj->Svar_plus();
echo "<hr>";
print "obj1->var1=".$obj->var1;
echo "<hr>";
print "obj::Svar1=";
$obj->print_Sva();

?>