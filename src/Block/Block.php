<?php
namespace Wsprofile\Block;

use Drupal\wconsumer\Service\Base as Service;



abstract class Block implements BlockInterface {
  const CACHE_LIFETIME = 86400;

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

  protected function fetch($url) {
    $account = menu_get_object('user');
    if (!$account) {
      throw new \RuntimeException("Can't detect which profile is being viewed");
    }

    $cacheId = md5(join('|', array(
      ($credentials = $this->service->getCredentials($account->uid)) ? $credentials->serialize() : null,
      $url
    )));

    $response = @cache_get($cacheId)->data;
    if (!$response) {
      $api = $this->service->api($account->uid, $this->scopes());
      $response = $api->get($url)->send()->json();
      cache_set($cacheId, $response, 'cache', time() + self::CACHE_LIFETIME);
    }

    return $response;
  }
}