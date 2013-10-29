<?php
namespace Wsprofile\Block;

use Drupal\wconsumer\Wconsumer;
use Guzzle\Http\Client;



class Github extends Block {
  public function scopes() {
    return array();
  }

  public function render(\stdClass $account) {
    $url = null; {
      $user = $this->fetch('/user', $account);
      $url = @$user['html_url'];
      if (!isset($url)) {
        throw new \RuntimeException("No html_url field in GitHub '/user' endpoint response");
      }
    }

    /** @var Client $client */
    $client = Wconsumer::instance()->container['httpClient'];
    $html = $client->get($url)->send()->getBody(true);

    $list = array(); {
      $matches = array();
      if (preg_match('/contrib-day.*?(\d+)\s+Total/si', $html, $matches)) {
        $list[] = array('Total Contributions', $matches[1]);
      }

      $matches = array();
      if (preg_match('/contrib-streak(?!-).*?(\d+)\s+day/si', $html, $matches)) {
        $list[] = array('Longest Streak', $matches[1]);
      }
    }

    return $this->doRender($list, $url);
  }
}
