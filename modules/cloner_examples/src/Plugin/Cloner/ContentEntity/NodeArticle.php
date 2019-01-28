<?php

namespace Drupal\cloner_examples\Plugin\Cloner\ContentEntity;

use Drupal\cloner\Annotation\ClonerContentEntity;
use Drupal\cloner\Plugin\Cloner\ContentEntity\ClonerContentEntityPluginBase;
use Drupal\Core\Annotation\Translation;

/**
 * @ClonerContentEntity(
 *   id = "cloner_examples_node_article",
 *   label = @Translation("Clone node article"),
 * )
 */
class NodeArticle extends ClonerContentEntityPluginBase {

}
