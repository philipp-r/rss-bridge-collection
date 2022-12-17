<?php

class TraktCommentsBridge extends BridgeAbstract
{
    const MAINTAINER = 'philipp-r';
    const NAME = 'Trakt.tv Comments';
    const URI = 'https://trakt.tv/';
    const CACHE_TIMEOUT = 3600; // 1hrs
    const DESCRIPTION = 'Returns comments from a public profile.';

    const PARAMETERS = [ [
        'u' => [
            'type' => 'text',
            'name' => 'username',
            'exampleValue' => 'johndoe',
            'title' => 'The profile must be public (Trakt.tv &gt; Settings &gt; uncheck Private)',
        ],
    ] ];

    protected function getFullURI()
    {
        return $this->getURI()
        . 'users/' . urlencode($this->getInput('u')) . '/comments';
    }

    protected function getItemFromElement($element)
    {

        $item = [];
        $item['uri'] = html_entity_decode($element->find('meta[itemprop="url"]', 0)->content);
        $item['uid'] = $item['uri'];
        $item['content'] = $element->find('meta[itemprop="description"]', 0)->content;
        $item['author'] = urlencode($this->getInput('u'));
        $item['timestamp'] = $element->find('meta[itemprop="datePublished"]', 0)->content;
        $item['title'] = $element->find('div[class*="comment-wrapper"]',0)->find('meta[itemprop="name"]',1)->content;

        return $item;
    }

    public function collectData()
    {
        $html = getSimpleHTMLDOMCached( $this->getFullURI() );

        foreach ( $html->find('div[class*="comment-with-poster"]') as $element ) {
            $this->items[] = $this->getItemFromElement($element);
        }
    }
}
