<?php

namespace Drupal\cloner_examples\Plugin\Cloner\ContentEntity;

use Drupal\cloner\Annotation\ClonerContentEntity;
use Drupal\cloner\Plugin\Cloner\ContentEntity\ClonerContentEntityPluginBase;
use Drupal\Core\Annotation\Translation;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Form\FormStateInterface;

/**
 * @ClonerContentEntity(
 *   id = "cloner_examples_node_article",
 *   label = @Translation("Clone node article"),
 * )
 */
class NodeArticle extends ClonerContentEntityPluginBase {

  /**
   * {@inheritdoc}
   */
  public function buildCloneForm(array $form, FormStateInterface $form_state) {
    $form = parent::buildCloneForm($form, $form_state);
    $entity = $this->getEntity();

    $form['new_title'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Title for cloned entity'),
      '#required' => TRUE,
      '#default_value' => $entity->label(),
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function processClone(EntityInterface $entity_source, EntityInterface $entity_destination, array $context = []) {
    if (isset($context['form_state'])) {
      /** @var \Drupal\Core\Form\FormStateInterface $form_state */
      $form_state = $context['form_state'];
      $entity_destination->setTitle($form_state->getValue('new_title'));
    }
  }

}
