<?php
namespace Wsprofile;

use Drupal\wconsumer\Service\Service;
use Drupal\wconsumer\Wconsumer;



class Wsprofile {
  private static $instance;

  /** @var Block\BlockInterface[] */
  private $blocks;



  public static function instance() {
    if (!isset(self::$instance)) {
      self::$instance = new self();
    }

    return self::$instance;
  }

  public function defineRequiredScopes(Service $service) {
    $block = @$this->blocks[$service->getName()];
    if (!isset($block)) {
      return null;
    }

    return $block->scopes();
  }

  public function defineBlocks() {
    $blocks = array();

    foreach ($this->blocks as $block) {
      $service = $block->getService();

      $blocks[$service->getName()] = array(
        'info' => t(
          "@service profile",
          array('@service' => $service->getMeta()->niceName)
        ),
        'cache' => DRUPAL_CACHE_CUSTOM,
      );
    }

    return $blocks;
  }

  public function viewBlock($name) {
    $account = menu_get_object('user');
    if (!$account || !$account->uid) {
      return null;
    }

    $block = @$this->blocks[$name];
    if (!isset($block)) {
      return null;
    }

    try {
      return array(
        'subject' => $block->getService()->getMeta()->niceName,
        'content' => $block->render($account),
      );
    }
    catch (\Exception $e) {
      return null;
    }
  }

  private function __construct() {
    $blocks = array(
      Wconsumer::$linkedin->getName() => new Block\Linkedin(Wconsumer::$linkedin),
      Wconsumer::$github->getName()   => new Block\Github(Wconsumer::$github),
    );

    foreach ($blocks as &$block) {
      $block = new Block\CacheWrapper($block);
    }

    $this->blocks = $blocks;
  }
}
