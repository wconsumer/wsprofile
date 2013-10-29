<?php
namespace Wsprofile\Block;



class Linkedin extends Block {
  public function scopes() {
    return array('r_basicprofile', 'r_fullprofile');
  }

  public function render(\stdClass $account) {
    $response = null; {
      $fields = 'num-connections,num-connections-capped,educations,positions,public-profile-url';
      $url = "people/~:({$fields})?format=json";
      $response = $this->fetch($url, $account);
    }

    $list = array(); {
      if (isset($response['numConnections'])) {
        $list[] = array('Connections', $response['numConnections'] . ($response['numConnectionsCapped'] ? '+' : ''));
      }

      if ($education = @reset($response['educations']['values'])) {
        $list[] = array('Education', $education['schoolName']);
      }

      if ($position = @reset($response['positions']['values'])) {
        $list[] = array('Last Employer', $position['company']['name']);
      }
    }

    return $this->doRender($list, @$response['publicProfileUrl']);
  }
}