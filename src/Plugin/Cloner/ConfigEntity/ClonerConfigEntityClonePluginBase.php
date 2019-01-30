<?php

namespace Drupal\cloner\Plugin\Cloner\ConfigEntity;

use Drupal\cloner\Plugin\Cloner\ClonerClonePluginBase;
use Drupal\cloner\Plugin\Cloner\ContentEntity\ClonerContentEntityClonePluginBaseInterface;

/**
 * The base for ConfigEntity cloner plugins.
 *
 * @package Drupal\cloner\Plugin\Cloner\ContentEntity
 */
abstract class ClonerConfigEntityClonePluginBase extends ClonerClonePluginBase implements ClonerContentEntityClonePluginBaseInterface {

  // @todo add methods to get config id and label names.

}
