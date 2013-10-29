<?php
namespace Wsprofile\Block;

use Drupal\wconsumer\Service\Base as Service;



class CacheWrapper implements BlockInterface {
  const CACHE_LIFETIME = 86400;

  private $block;



  public function __construct(BlockInterface $block) {
    $this->block = $block;
  }

  public function scopes() {
    return $this->block->scopes();
  }

  public function render(\stdClass $account) {
    $cacheId = "wsprofile_user_{$this->getService()->getName()}_{$account->uid}";
    $renderResult = @cache_get($cacheId)->data;
    if (!$renderResult) {
      $renderResult = $this->block->render($account);
      cache_set($cacheId, $renderResult, 'cache', time() + self::CACHE_LIFETIME);
    }

    return $renderResult;
  }

  public function getService() {
    return $this->block->getService();
  }

  public function setService(Service $service) {
    $this->block->setService($service);
  }
}