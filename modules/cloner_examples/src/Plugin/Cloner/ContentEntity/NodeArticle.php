<?php

declare(strict_types = 1);

namespace Drupal\cloner_examples\Plugin\Cloner\ContentEntity;

use Drupal\cloner\Plugin\Cloner\ContentEntity\ClonerContentEntityClonePluginBase;
use Drupal\Core\Entity\EntityInterface;

/**
 * Defines the 'cloner_examples_node_article' entity cloner.
 *
 * @ClonerContentEntity(
 *   id = "cloner_examples_node_article",
 *   label = @Translation("Clone node article"),
 * )
 */
class NodeArticle extends ClonerContentEntityClonePluginBase {

  /**
   * {@inheritdoc}
   */
  public function cloneEntity(EntityInterface $entity_source, EntityInterface $entity_destination, array $context = []): void {
    // If executed via ClonerForm.
    // @see \Drupal\cloner_examples\Plugin\Cloner\Form\NodeArticleCloneForm
    if (isset($context['form_state'])) {
      /** @var \Drupal\Core\Form\FormStateInterface $form_state */
      $form_state = $context['form_state'];
      $entity_destination->setTitle($form_state->getValue('new_title'));
    }
  }

}
