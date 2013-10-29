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
}