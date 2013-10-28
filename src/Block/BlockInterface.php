<?php
namespace Wsprofile\Block;

use Drupal\wconsumer\Service\Base as Service;


interface BlockInterface {
  public function scopes();
  public function render();

  /** @return Service */
  public function getService();
  public function setService(Service $service);
}