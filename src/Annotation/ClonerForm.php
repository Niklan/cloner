<?php declare(strict_types = 1);

namespace Drupal\cloner\Annotation;

use Drupal\Component\Annotation\Plugin;

/**
 * Provides cloner form plugin annotation.
 *
 * @Annotation
 */
final class ClonerForm extends Plugin {

  /**
   * The plugin ID.
   */
  public string $id;

  /**
   * The plugin label.
   */
  public string $label;

  /**
   * The cloner plugin type to execute.
   *
   * Value must be "content_entity" or "config_entity".
   */
  public string $cloner_plugin_type;

  /**
   * The cloner plugin id to execute.
   *
   * This plugin will be executed on successful submission and get values from
   * the form.
   */
  public string $cloner_plugin_id;

  /**
   * The entity operation label.
   *
   * If set, this label will be added to entity operations that links to clone
   * form.
   */
  public string $entity_operation_label;

  /**
   * The plugin status.
   *
   * By default all plugins are enabled and this value set to TRUE. You can set
   * it to FALSE, to temporary disable plugin.
   */
  public bool $enabled;

  /**
   * The wight of plugin.
   *
   * Plugin with higher with, will be selected. By default set to 0.
   */
  public int $weight;

}
