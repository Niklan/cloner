<?php

namespace Drupal\cloner_examples\Plugin\Cloner\Form;

use Drupal\cloner\Annotation\ClonerForm;
use Drupal\cloner\Plugin\Cloner\Form\ClonerFormPluginBase;
use Drupal\Core\Annotation\Translation;
use Drupal\Core\Form\FormStateInterface;

/**
 * @ClonerForm(
 *   id = "cloner_examples_node_article_clone_form",
 *   label = @Translation("Clone node article"),
 *   cloner_plugin_type = "content_entity",
 *   cloner_plugin_id = "cloner_examples_node_article"
 * )
 */
class NodeArticleCloneForm extends ClonerFormPluginBase {

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
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
  public function validateForm(array &$form, FormStateInterface $form_state) {
    if ($form_state->getValue('new_title') == $this->getEntity()->label()) {
      $form_state->setErrorByName('new_title', t('Title for new entity must be different from original.'));
    }
  }

}
