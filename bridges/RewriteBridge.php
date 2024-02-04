<?php

class RewriteBridge extends FeedExpander
{
    const MAINTAINER = 'Johan, Planchon';
    const NAME = 'Rewrite';
    const CACHE_TIMEOUT = 3600; // 1h
    const DESCRIPTION = 'Rewrite a feed of your choice';
    const URI = 'https://github.com/RSS-Bridge/rss-bridge';

    const PARAMETERS = [[
        'url' => [
            'name' => 'Feed URL',
            'type' => 'text',
            'exampleValue' => 'https://lorem-rss.herokuapp.com/feed?unit=day',
            'required' => true,
        ],
        'match_uris' => [
            'name' => 'Matcher (regular expressions in JSON!!!)',
            'required' => false,
        ],
        'to_uris' => [
            'name' => 'Replacement (use $n for matching result, JSON!!)',
            'required' => false,
        ],
        'match_contents' => [
            'name' => 'Replace content (regular expressions in JSON!!!)',
            'required' => false,
        ],
        'to_contents' => [
            'name' => 'Replacement (use $n for matching result, JSON!!)',
            'required' => false,
        ],
    ]];

    public function collectData()
    {
        $url = $this->getInput('url');
        if (!Url::validate($url)) {
            returnClientError('The url parameter must either refer to http or https protocol.');
        }
        $this->collectExpandableDatas($this->getURI());
    }

    protected function parseItem(array $item)
    {
        $uri_matcher = $this->getInput('match_uris');
        $uri_replacement = $this->getInput('to_uris');
        if ($uri_matcher && $uri_replacement) {
            $item['uri'] = preg_replace(json_decode($uri_matcher), json_decode($uri_replacement), $item['uri']);
        }

        $content_matcher = $this->getInput('match_contents');
        $content_replacement = $this->getInput('to_contents');
        if ($content_matcher && $content_replacement) {
            $item['content'] = preg_replace(json_decode($content_matcher), json_decode($content_replacement), $item['content']);
        }

        return $item;
    }

    public function getURI()
    {
        $url = $this->getInput('url');

        if (empty($url)) {
            $url = parent::getURI();
        }

        return $url;
    }
}
