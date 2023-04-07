<?php declare(strict_types = 1);

namespace Drupal\cloner\Annotation;

use Drupal\Component\Annotation\Plugin;

/**
 * Provides cloner config entity plugin annotation.
 *
 * @Annotation
 */
final class ClonerConfigEntity extends Plugin {

  /**
   * The plugin ID.
   */
  public string $id;

  /**
   * The plugin label.
   */
  public string $label;

}
