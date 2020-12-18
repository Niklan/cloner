<?php

namespace Drupal\cloner_examples\Plugin\Cloner\Form;

use Drupal\cloner\Plugin\Cloner\Form\ClonerFormPluginBase;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\Core\Form\FormStateInterface;

/**
 * @ClonerForm(
 *   id = "cloner_examples_image_style",
 *   label = @Translation("Clone image style"),
 *   cloner_plugin_type = "config_entity",
 *   cloner_plugin_id = "cloner_example_image_style",
 *   entity_operation_label = @Translation("Clone")
 * )
 */
class ImageStyleCloneForm extends ClonerFormPluginBase {

  /**
   * {@inheritdoc}
   */
  public static function isApplicable(EntityTypeInterface $entity_type, EntityInterface $entity) {
    return $entity_type->id() == 'image_style';
  }

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

    $form['machine_name'] = [
      '#type' => 'textfield',
      '#default_value' => $entity->id(),
      '#required' => TRUE,
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    if ($form_state->getValue('new_title') == $this->getEntity()->label()) {
      $form_state->setErrorByName('new_title', $this->t('Title for new entity must be different from original.'));
    }

    // @todo add entityTypeManager for check.
    if ($form_state->getValue('machine_name') == $this->getEntity()->id()) {
      $form_state->setErrorByName('machine_name', $this->t('The config entity id must be unique'));
    }
  }

}
