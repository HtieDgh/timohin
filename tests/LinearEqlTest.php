<?php namespace unittest;
use \PHPUnit\Framework\TestCase;
class LinearEqlTest extends TestCase{
    protected $fixture,$e;

    protected function setUp() :void
    {
        $this->fixture = new \timohin\LinearEql();
        $this->e = \timohin\TimohinExeption::class;
    }

    protected function tearDown():void
    {
        $this->fixture = NULL;
        $this->e=NULL;
    }
    
    /**
    * @dataProvider providerSolveGoodNums
    */
    public function testSolveGoodNums($a,$b,$res)
    {
        
        $this->assertEquals($res,$this->fixture->solve($a,$b));
    }
    public function providerSolveGoodNums()
    {
        return array(
            array(2, 4, [-2]),
            array(11.5,-23, [2]),
            array(0, 0, [INF])
        );
    }
    /**
     * @dataProvider providerSolveBadNums
     */
    public function testSolveBadNums($a,$b,$msg)
    {
        $this->expectExceptionMessage($msg,$this->e);
        $this->fixture->solve($a,$b);
    }
    public function providerSolveBadNums()
    {
        return array(
            array(0, 7, "Error: eq not exist"),
            array(0, 8, "Error: eq not exist")
        );
    }
}
?>