<?php

namespace Drupal\cloner\Annotation;

use Drupal\Component\Annotation\Plugin;

/**
 * ClonerContentEntity annotation.
 *
 * @Annotation
 */
class ClonerContentEntity extends Plugin {

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

}
