<?php namespace unittest;
use \PHPUnit\Framework\TestCase;
class _QuadricEql extends \timohin\QuadricEql{
    public function getDesc($a,$b,$c)
    {
        return $this->desc($a,$b,$c);
    }
}
class QuadricEqlTest extends TestCase{
    protected $fixture,$e;

    protected function setUp() :void
    {
        $this->fixture = new _QuadricEql();
        $this->e = \timohin\TimohinExeption::class;
    }

    protected function tearDown():void
    {
        $this->fixture = NULL;
        $this->e=NULL;
    }
    /**
    * @dataProvider providerDescGoodNums
    */
    public function testDesc($a,$b,$c,$res)
    {
        $this->assertEquals($res,$this->fixture->getDesc($a,$b,$c));
    }
    public function providerDescGoodNums()
    {
        return array(
            array(1, 10, 5, 80),
            array(5, 3, 10, -191)
        );
    }
   
    /**
    * @dataProvider providerSolveGoodNums
    */
    public function testSolveGoodNums($a,$b,$c,$res)
    {
        $this->assertEquals($res,$this->fixture->solve($a,$b,$c));
    }
    public function providerSolveGoodNums()
    {
        return array(
            array(1, 0, -9,[-3,3]),
            array(1, 2, 1,[-1]),
            array(4, 4, 1, [-0.5]),
            array(0, 2, 4, [-2]),
            array(0, 0, 0,[INF])
        );
    }
    /**
     * @dataProvider providerSolveBadNums
     */
    public function testSolveBadNums($a,$b,$c,$msg)
    {
        $this->expectExceptionMessage($msg,$this->e);
        $this->fixture->solve($a,$b,$c);
    }
    public function providerSolveBadNums()
    {
        return array(
            array(5,3, 10, 'Error: no real roots'),
            array(1,10, 50, 'Error: no real roots'),
            array(0,0, 7, 'Error: eq not exist'),
            array(0,0, 8, 'Error: eq not exist')
        );
    }
}
?>