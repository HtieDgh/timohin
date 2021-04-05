<?php namespace unittest;
use \PHPUnit\Framework\TestCase;
use \timohin\MyLog as ML;
class _ML extends ML{
    public static function getLog()
    {
        return self::Instance()->log;
    }
}
class MyLogTest extends TestCase{
    public static $log=[];
    /**
     * Scans dir with additional params
     * @param dir path
     * @param exp req_exp
     * @param how string
     * @param desc bool
     * @return array
     */
    protected function _scandir($dir, $exp, $how='name', $desc=0)
    {
        $r = array();
        $dh = @opendir($dir);
        if ($dh){
            while(($fname = readdir($dh)) !== false) {
                if (preg_match($exp, $fname)) {
                    $stat = stat("$dir/$fname");
                    $r[$fname] = ($how == 'name')? $fname: $stat[$how];
                }
            }
            closedir($dh);
            if ($desc){
                arsort($r);
            }else{
                asort($r);
            }
        }
        return(array_keys($r));
    }
    public function testInstance()
    {
        $this->assertInstanceOf(\core\LogAbstract::class, _ML::Instance());
    }
    /**
    * @dataProvider providerLog
    */
    public function testLog($msg)
    {
        _ML::log($msg);
        self::$log[]=['msg'=>$msg];
        $this->assertSame(self::$log,_ML::getLog());
    }
    public function providerLog()
    {
        return array(
            array("teststr"),
            array("test?te_123"),
            array("teststr..znhui30::\\}{;'~")
        );
    }
    /**
     * @depends testLog
     */
    public function testWrite()
    {
        $_tmpLogTxt='';
        foreach(self::$log as $v){
            $_tmpLogTxt.="{$v['msg']}\r\n";
        }
        $this->expectOutputString($_tmpLogTxt);
        _ML::write();
        $_tmpLogTxt=rtrim($_tmpLogTxt);
        $this->assertDirectoryExists('log');
        $_tmpLogFile=$this->_scandir(
            'log\\',
            '/'.date('d-m-Y\TH').'[0-9T.]*\.log$/',
            'ctime',
            1
        )[0];
        $this->assertFileExists('log/'.$_tmpLogFile);
        $this->assertIsReadable('log/'.$_tmpLogFile);
        $this->assertStringEqualsFile('log/'.$_tmpLogFile,$_tmpLogTxt);
    }
}
?>