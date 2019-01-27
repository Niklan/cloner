<?php

namespace Drupal\cloner\Annotation;

use Drupal\Component\Annotation\Plugin;

/**
 * ClonerConfigEntity annotation.
 *
 * @Annotation
 */
class ClonerConfigEntity extends Plugin {

  /**
   * The plugin ID.
   *
   * @var string
   */
  public $id;

  /**
   * The plugin label.
   *
   * @var string
   */
  public $label;

  /**
   * The plugin status.
   *
   * By default all plugins are enabled and this value set to TRUE. You can set
   * it to FALSE, to temporary disable plugin.
   *
   * @var bool
   */
  public $enabled;

  /**
   * The wight of plugin.
   *
   * Plugin with higher with, will be selected. By default set to 0.
   *
   * @var int
   */
  public $weight;

}
