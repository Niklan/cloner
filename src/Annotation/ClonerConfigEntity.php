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
  public string $id;

  /**
   * The plugin label.
   *
   * @var string
   */
  public string $label;

}
