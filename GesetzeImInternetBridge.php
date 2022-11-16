<?php

class GesetzeImInternetBridge extends FeedExpander
{
    const MAINTAINER = 'philipp-r';
    const NAME = 'Gesetze im Internet Aktualitätendienst';
    const URI = 'https://www.gesetze-im-internet.de/';
    const CACHE_TIMEOUT = 86400; // 24hrs
    const DESCRIPTION = 'Tagesaktuelle Hinweise auf neu im Bundesgesetzblatt verkündete Dokumente mit einer Filtermöglichkeit.';
    const PARAMETERS = [ [
        's' => [
            'type' => 'text',
            'required' => false,
            'exampleValue' => 'Gesetz',
            'name' => 'Include only if title contains'
        ],
        'x' => [
            'type' => 'text',
            'required' => false,
            'exampleValue' => 'Sozialgesetzbuch',
            'name' => 'Exclude if title contains'
        ]
    ]];

    protected function parseItem($feedItem){
      $item = parent::parseItem($feedItem);
      // Test if string contains the word
      if (
        ( empty($this->getInput('s')) ||  str_contains($item['description'], $this->getInput('s')) ) &&
        ( empty($this->getInput('x')) || !str_contains($item['description'], $this->getInput('x')) )
      ){
          return $item;
      }
      else{
          return NULL;
      }
    }

    public function collectData()
    {
        $this->collectExpandableDatas('https://www.gesetze-im-internet.de/aktuDienst-rss-feed.xml');

    }
}
