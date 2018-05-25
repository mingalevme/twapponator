<?php

namespace Twapponator\Tests;

use PHPUnit\Framework\TestCase;
use Twapponator\Exception;
use Twapponator\Twapponator;

class Test extends TestCase
{
    public function testObtainingBearerToken()
    {
        if (!($consumerKey = getenv('TWAPPONATOR_TEST_CONSUMER_KEY'))) {
            $this->fail('Env var TWAPPONATOR_TEST_CONSUMER_KEY is not set');
        }

        if (!($consumerSecret = getenv('TWAPPONATOR_TEST_CONSUMER_SECRET'))) {
            $this->fail('Env var TWAPPONATOR_TEST_CONSUMER_SECRET is not set');
        }

        $token = Twapponator::obtainBearerToken($consumerKey, $consumerSecret);

        $this->assertInternalType('string', $token);
        $this->assertGreaterThan(1, strlen($token));

        return $token;
    }

    /**
     * @depends testObtainingBearerToken
     */
    public function testGettingTweet($token)
    {
        $tweetId = '994427418440974336';

        $twapponator = new Twapponator($token);

        $tweet = $twapponator->status($tweetId);

        $this->assertInternalType('array', $tweet);
    }

    public function testInvalidCredentials()
    {
        $this->expectException(Exception::class);
        Twapponator::obtainBearerToken('key', 'secret');
    }
}
