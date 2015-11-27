<?php
use ActionKit\ValueType\BoolType;
use ActionKit\ValueType\StrType;
use ActionKit\ValueType\FileType;
use ActionKit\ValueType\NumType;
use ActionKit\ValueType\UrlType;
use ActionKit\ValueType\IpType;
use ActionKit\ValueType\Ipv4Type;
use ActionKit\ValueType\Ipv6Type;
use ActionKit\ValueType\EmailType;
use ActionKit\ValueType\PathType;

class ValueTypeTest extends PHPUnit_Framework_TestCase
{

    public function testTypeClass() 
    {
        $this->assertNotNull(new BoolType);
        $this->assertNotNull(new StrType);
        $this->assertNotNull(new FileType );
        $this->assertNotNull(new NumType );
        $this->assertNotNull(new UrlType );
        $this->assertNotNull(new IpType );
        $this->assertNotNull(new Ipv4Type );
        $this->assertNotNull(new Ipv6Type );
        $this->assertNotNull(new EmailType);
        $this->assertNotNull(new PathType);
    }

    public function testBoolType()
    {
        $bool = new BoolType;
        $this->assertTrue( $bool->test('true') );
        $this->assertTrue( $bool->test('false') );
        $this->assertTrue( $bool->test('0') );
        $this->assertTrue( $bool->test('1') );
        $this->assertFalse( $bool->test('foo') );
        $this->assertFalse( $bool->test('123') );
    }

    public function testPathType()
    {
        $url = new PathType;
        $this->assertTrue( $url->test('tests') );
        $this->assertTrue($url->test('composer.json') );
        $this->assertFalse($url->test('foo/bar'));
    }

    public function testUrlType()
    {
        $url = new UrlType;
        $this->assertTrue($url->test('http://t'));
        $this->assertTrue($url->test('http://t.c'));
        $this->assertFalse($url->test('t.c'));
    }

    public function testIpType()
    {
        $ip = new IpType;
        $this->assertTrue( $ip->test('192.168.25.58') );
        $this->assertTrue( $ip->test('2607:f0d0:1002:51::4') );
        $this->assertTrue( $ip->test('::1') );
        $this->assertFalse($ip->test('10.10.15.10/16'));
    }

    public function testIpv4Type()
    {
        $ipv4 = new Ipv4Type;
        $this->assertTrue( $ipv4->test('192.168.25.58') );
        $this->assertTrue( $ipv4->test('8.8.8.8') );
        $this->assertFalse($ipv4->test('2607:f0d0:1002:51::4'));
    }

    public function testIpv6Type()
    {
        $ipv6 = new Ipv6Type;
        $this->assertTrue($ipv6->test('2607:f0d0:1002:51::4') );
        $this->assertTrue($ipv6->test('2607:f0d0:1002:0051:0000:0000:0000:0004') );
        $this->assertFalse($ipv6->test('192.168.25.58'));
    }

    public function testEmailType()
    {
        $email = new EmailType;
        $this->assertTrue($email->test('test@gmail.com'));
        $this->assertFalse($email->test('test@test'));
    }
}

