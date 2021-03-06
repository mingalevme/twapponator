<?php

namespace Twapponator;

abstract class Core
{
    const TWITTER_OAUTH_TOKEN_URL = 'https://api.twitter.com/oauth2/token';
    
    /**
     * @var string
     */
    protected $token;

    public static function obtainBearerToken($consumerKey, $consumerSecret)
    {
        $credentials = base64_encode(rawurlencode($consumerKey) . ':' . rawurlencode($consumerSecret));
        
        $ch = curl_init(self::TWITTER_OAUTH_TOKEN_URL);
        
        curl_setopt($ch, \CURLOPT_POST, 1);
        curl_setopt($ch, \CURLOPT_POSTFIELDS, 'grant_type=client_credentials');
        curl_setopt($ch, \CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, \CURLOPT_HTTPHEADER, array(
            "User-Agent: twapponator",
            "Authorization: Basic {$credentials}",
            "Content-Type: application/x-www-form-urlencoded; charset=UTF-8"
        ));
            
        $response = curl_exec($ch);
        $info = curl_getinfo($ch);
        curl_close($ch);
        
        if ($response === false || is_array($info) === false || $info['http_code'] !== 200) {
            self::throwException($response, $info);
        }
        
        $data = json_decode($response, true);
        
        return $data['access_token'];
    }
    
    /**
     * @param string $response
     * @param array $info
     * @throws Exception
     */
    protected static function throwException($response, $info)
    {
        if (is_string($response)) {
            try {
                @$data = \json_decode($response, true);
            } catch (\Exception $e) {
                throw new Exception("Unknown error: {$response}");
            }
            if (isset($data['errors'][0])) {
                throw new Exception($data['errors'][0]['message'], $data['errors'][0]['code']);
            }
        } else {
            $httpErrorCode = isset($info['http_code']) ? $info['http_code'] : 500;
            
            if ($httpErrorCode === 401) {
                throw new Exception('Using an incorrect or revoked bearer token to make API request');
            }
            
            if ($httpErrorCode === 403) {
                throw new Exception('Obtain or revoke a bearer token with incorrect or expired app credentials. Or Obtain a bearer token too frequently in a short period of time. Or Requesting an endpoint which requires a user context');
            }
        }
        
        throw new Exception('Unknown Connection Error' . (is_string($response) ? ": {$response}" : ''));
    }

    /**
     * 
     * @param string $token
     * @throws Exception
     */
    public function __construct($token)
    {
        if (is_string($token) === false || trim($token) === '') {
            throw new Exception('Token is invalid');
        }
        
        $this->token = $token;
    }
    
    public function request($url)
    {
        $ch = curl_init($url);
        
        curl_setopt($ch, \CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, \CURLOPT_HTTPHEADER, array(
            "User-Agent: twapponator",
            "Authorization: Bearer {$this->token}",
        ));
            
        $response = curl_exec($ch);
        $info = curl_getinfo($ch);
        curl_close($ch);
        
        if ($response === false || is_array($info) === false || $info['http_code'] !== 200) {
            self::throwException($response, $info);
        }
        
        return json_decode($response, true);
    }
}
