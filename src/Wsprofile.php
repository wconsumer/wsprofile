<?php
namespace Wsprofile;

use Drupal\wconsumer\Service\Base as Service;
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
      );
    }

    return $blocks;
  }

  public function viewBlock($name) {
    $block = @$this->blocks[$name];
    if (!isset($block)) {
      return null;
    }

    try {
      return array(
        'subject' => $block->getService()->getMeta()->niceName,
        'content' => $block->render(),
      );
    }
    catch (\Exception $e) {
      return null;
    }
  }

  private function __construct() {
    $this->blocks = array(
      Wconsumer::$linkedin->getName() => new Block\Linkedin(Wconsumer::$linkedin),
      Wconsumer::$github->getName()   => new Block\Github(Wconsumer::$github),
    );
  }
}
