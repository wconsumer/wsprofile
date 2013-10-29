<?php
namespace Wsprofile\Block;

use Drupal\wconsumer\Service\Base as Service;



abstract class Block implements BlockInterface {
  /** @var Service */
  protected $service;



  public function __construct(Service $service) {
    $this->setService($service);
  }

  public function setService(Service $service) {
    $this->service = $service;
  }

  public function getService() {
    return $this->service;
  }

  protected function fetch($url, \stdClass $account) {
    $api = $this->service->api($account->uid, $this->scopes());
    $response = $api->get($url)->send()->json();
    return $response;
  }

  protected function doRender(array $list, $url) {
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
        <? if (!empty($url)): ?>
          <a href="<?= check_plain($url) ?>" target="_blank">Visit Profile</a>
        <? endif; ?>
      <?php
    return ob_get_clean();
  }
}