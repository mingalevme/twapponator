<?php

namespace Twapponator;

class Twapponator extends Core
{
    const STATUSES_SHOW = 'https://api.twitter.com/1.1/statuses/show.json';
    
    /**
     * Returns a single Tweet, specified by the id parameter. The Tweet’s author will also be embedded within the tweet.
     * @param string $id
     * @return array
     */
    public function status($id)
    {
        return $this->request(self::STATUSES_SHOW . '?tweet_mode=extended&id=' . $id);
    }
}
