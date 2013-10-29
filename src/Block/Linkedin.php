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

    if (isset($response['numConnections'])) {
      $list[] = array('Connections', $response['numConnections'] . ($response['numConnectionsCapped'] ? '+' : ''));
    }

    if ($education = @reset($response['educations']['values'])) {
      $list[] = array('Education', $education['schoolName']);
    }

    if ($position = @reset($response['positions']['values'])) {
      $list[] = array('Last Employer', $position['company']['name']);
    }

    ob_start();
      ?>
        <? if (!empty($list)): ?>
          <ul>
            <? foreach ($list as $item): ?>
              <li>
                  <?= check_plain($item[1]) ?>
                  <span><?= check_plain($item[0]) ?></span>
              </li>
            <? endforeach; ?>
          </ul>
        <? endif; ?>
        <? if (!empty($response['publicProfileUrl'])): ?>
          <a href="<?= check_plain($response['publicProfileUrl']) ?>" target="_blank">Visit Profile</a>
        <? endif; ?>
      <?php
    $html = ob_get_clean();

    return $html;
  }
}