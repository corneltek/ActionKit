<?php


namespace ActionKit\Csrf;

class CsrfTokenProviderTest extends \PHPUnit\Framework\TestCase 
{
    protected $token;

    protected $provider;

    public function setUp()
    {
        // $storage = new CsrfSessionStorage('__csrf_token');
        $storage = new CsrfArrayStorage('__csrf_token');
        $this->provider = new CsrfTokenProvider($storage);
        $this->provider->setTtl(1800);
        $this->token = $this->provider->loadCurrentToken(true);
    }

    public function testArrayStorage()
    {
        $storage = new CsrfArrayStorage('__csrf_token');
        $provider = new CsrfTokenProvider($storage);
        $token = $provider->loadCurrentToken();
        $this->assertTrue($storage->exists(), '__csrf_token should exists');
    }

    public function testGenerateToken() 
    {
        $token = $this->token;
        $this->assertNotNull($token); 
        $this->assertEquals(1800, $token->ttl);
        $this->assertNotNull($token->hash);
        $this->assertNotNull($this->provider->loadCurrentToken());
    }

    public function testVerifyToken()
    {
        $ret = $this->provider->isValidToken($this->token->hash, time());
        $this->assertTrue($ret);
    }
}
