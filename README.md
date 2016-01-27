Twapponator - TWitter APPlication ONly authenticATOR
===

## Weclcome

**Twapponator** - Very simple PHP Twitter [Application Only](https://dev.twitter.com/oauth/application-only) Authentication Client.

## Installing via Composer

The recomended way to install **Twapponator** is through [Composer](http://getcomposer.org/). 

```
# Install Composer
curl -sS https://getcomposer.org/installer | php

# Add Twapponator as a dependency
php composer.phar require mingalevme/twapponator:dev-master
```

After installing, you need to require Composer's autoloader:

```
require 'vendor/autoload.php';
```


## Dependencies

```
"php": ">=5.3.0"
"ext-curl": "*"
```

## Basic Usage

Once installed you can easily access all of the Twitter API endpoints supported by [Application Only Authentication](https://dev.twitter.com/oauth/application-only). You can view those enpoints [here](https://dev.twitter.com/docs/rate-limiting/1.1/limits). 

```php
<?php

// Obtain Bearer Token (if needed)
$token = \Twapponator\Client::obtainBearerToken('consumer_key', 'consumer_secret');

// Now you can cache it for future use
$someCacheStorage->set('twitter_bearer_token', $token);


// Init Twapponator Object
$twapponator = new \Twapponator\Client($token);

// Request API Enpoint data
try {
    $data = $twapponator->request('https://api.twitter.com/1.1/statuses/show.json?id=' . $someTweetId);
} catch (\Twapponator\Exception $e) {
    echo $e->getMessage();
    exit();
}

// For some endpoints there are shortcuts.
// See list of available shortcuts in \Twapponator\Client class source code.
// Next row is equal to $twapponator->request('https://api.twitter.com/1.1/statuses/show.json?id=' . $someTweetId);
$data = $twapponator->status($someTweetId);

echo $response;
```

## Unit Testing
Coming Soon
