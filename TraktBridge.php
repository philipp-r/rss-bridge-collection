<?php

class TraktBridge extends BridgeAbstract
{
    const MAINTAINER = 'philipp-r';
    const NAME = 'Trakt.tv';
    const URI = 'https://trakt.tv/';
    const CACHE_TIMEOUT = 3600; // 1hrs
    const DESCRIPTION = 'Returns history or ratings for a public profile.';

    const PARAMETERS = [ [
        'u' => [
            'type' => 'text',
            'name' => 'username',
            'exampleValue' => 'johndoe',
            'title' => 'The profile must be public (Trakt.tv &gt; Settings &gt; uncheck Private)',
            'required' => true,
        ],
        'c' => [
            'name' => 'contents',
            'type' => 'list',
            'required' => true,
            'values' => array(
              'history' => 'history',
              'ratings' => 'ratings'
            )
        ],
    ] ];

    protected function getFullURI()
    {
        return $this->getURI()
        . 'users/' . urlencode($this->getInput('u'))
        . '/' . urlencode($this->getInput('c'));
    }

    protected function getItemFromElement($element)
    {

        $item = [];
        $item['uri'] = html_entity_decode($element->find('meta[itemprop="url"]', 0)->content);
        $item['uid'] = $item['uri'];
        $item['author'] = urlencode($this->getInput('u'));
        $item['timestamp'] = $element->find('div[class*="titles"]',0)->find('span[class="format-date"]', 0)->innertext;

        $item['title'] = $element->find('meta[itemprop="name"]',0)->content;
        if($element->find('meta[itemprop="name"]',1)->content){
          // add the episode title
          $item['title'] .= ": ".$element->find('meta[itemprop="name"]',1)->content;
        }

        // ratings: only show number of stars
        if( $this->getInput('c') == "ratings" ){
          $item['content'] = strip_tags( $element->find('div[class*="titles"]',0)->find('h4[class="ellipsify"]', 1)->innertext );
        }
        //history: show title + season x episode
        elseif( $this->getInput('c') == "history" ){
          $item['content'] = $element->find('meta[itemprop="name"]',0)->content;
          if($element->find('div[class*="titles"]',0)->find('span[class="main-title-sxe"]', 0)->innertext){
            $item['content'] .= " ".$element->find('div[class*="titles"]',0)->find('span[class="main-title-sxe"]', 0)->innertext;
          }
        }

        return $item;
    }

    public function collectData()
    {
        $html = getSimpleHTMLDOMCached( $this->getFullURI() );

        foreach ( $html->find('div[class*="grid-item col-xs-6 col-md-2 col-sm-3"]') as $element ) {
            $this->items[] = $this->getItemFromElement($element);
        }
    }
}
