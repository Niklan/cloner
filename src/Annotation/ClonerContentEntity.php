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
  public string $id;

  /**
   * The plugin label.
   *
   * @var string
   */
  public string $label;

}
