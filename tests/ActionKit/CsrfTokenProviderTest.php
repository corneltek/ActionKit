<?php
use ActionKit\CsrfTokenProvider;

class CsrfTokenProviderTest extends PHPUnit_Framework_TestCase 
{
    protected $token;

    protected $tokenWithKey;

    protected $provider;

    public function setUp()
    {
        $this->provider = new CsrfTokenProvider;
        $this->token = $this->provider->generateToken();
        $this->tokenWithKey = $this->provider->generateToken('test_token', 500);
    }

    public function testGenerateToken() 
    {
        $token = $this->token;
        $this->assertNotNull($token); 
        $this->assertEquals('__csrf_token', $token->sessionKey);
        $this->assertEquals(1800, $token->ttl);
        ok($token->hash);
        ok($_SESSION['__csrf_token']);

        $tokenWithKey = $this->tokenWithKey;
        ok($tokenWithKey); 
        
        $this->assertEquals('test_token', $tokenWithKey->sessionKey);
        $this->assertEquals(500, $tokenWithKey->ttl);
        $this->assertNotNull($tokenWithKey->hash);
        ok($_SESSION['test_token']);
    }

    public function testLoadTokenWithSessionKey()
    {
        $token = $this->provider->loadToken();
        ok($token);
        $this->assertEquals($this->token->salt, $token->salt);

        $tokenWithHash = $this->provider->loadTokenWithSessionKey('__csrf_token', true);
        ok(isset($tokenWithHash->hash));
        is($this->token->hash, $tokenWithHash->hash);

        $tokenWithKey = $this->provider->loadTokenWithSessionKey('test_token');
        ok($tokenWithKey);
        is('test_token', $tokenWithKey->sessionKey);
        is($this->tokenWithKey->salt, $tokenWithKey->salt);
    }

    public function testVerifyToken()
    {
        $token = $this->provider->loadToken();
        $result = $this->provider->verifyToken($token, $this->token->hash, time());
        $this->assertTrue($result);
    }
}
