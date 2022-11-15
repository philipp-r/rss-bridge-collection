<?php

class GithubReleaseBridge extends FeedExpander
{
    const MAINTAINER = 'philipp-r';
    const NAME = 'Github Release';
    const URI = 'https://github.com/';
    const CACHE_TIMEOUT = 86400; // 24hrs
    const DESCRIPTION = 'Returns the releases of a GitHub project with the ability to filter.';
    const PARAMETERS = [ [
        'u' => [
            'name' => 'User name',
            'exampleValue' => 'RSS-Bridge',
            'required' => true
        ],
        'p' => [
            'name' => 'Project name',
            'exampleValue' => 'rss-bridge',
            'required' => true
        ],
        'rt' => [
            'name' => 'releases or tags',
            'type' => 'list',
            'required' => true,
            'values' => array(
              'releases' => 'releases',
              'tags' => 'tags'
            )
        ],
        's' => [
            'type' => 'text',
            'required' => false,
            'exampleValue' => 'stable',
            'name' => 'Include only if title contains'
        ],
        'x' => [
            'type' => 'text',
            'required' => false,
            'exampleValue' => 'rc',
            'name' => 'Exclude if title contains'
        ]
    ]];

    protected function parseItem($feedItem){
      $item = parent::parseItem($feedItem);
      // Test if string contains the word
      if (
        ( empty($this->getInput('s')) ||  str_contains($item['title'], $this->getInput('s')) ) &&
        ( empty($this->getInput('x')) || !str_contains($item['title'], $this->getInput('x')) )
      ){
          return $item;
      }
      else{
          return NULL;
      }
    }

    public function collectData()
    {
        $this->collectExpandableDatas('https://github.com/'.urlencode($this->getInput('u')).'/'.urlencode($this->getInput('p')).'/'.$this->getInput('rt').'.atom');

    }
}
