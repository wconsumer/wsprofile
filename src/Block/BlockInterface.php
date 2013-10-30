<?php
namespace Wsprofile\Block;

use Drupal\wconsumer\Service\Service;



interface BlockInterface {
  public function scopes();
  public function render(\stdClass $account);

  /** @return Service */
  public function getService();
  public function setService(Service $service);
}