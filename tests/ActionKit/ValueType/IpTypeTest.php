<?php
use ActionKit\ValueType\IpType;
use ActionKit\ValueType\Ipv4Type;
use ActionKit\ValueType\Ipv6Type;

class IpTypeTest extends PHPUnit_Framework_TestCase
{
    public function testTypeClass() 
    {
        $this->assertNotNull(new IpType );
        $this->assertNotNull(new Ipv4Type );
        $this->assertNotNull(new Ipv6Type );
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
}

