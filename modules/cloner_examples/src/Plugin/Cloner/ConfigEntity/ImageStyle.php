<?php declare(strict_types = 1);

namespace Drupal\cloner_examples\Plugin\Cloner\ConfigEntity;

use Drupal\cloner\Plugin\Cloner\ConfigEntity\ClonerConfigEntityClonePluginBase;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Form\FormStateInterface;

/**
 * Defines the 'cloner_example_image_style' entity cloner.
 *
 * @ClonerConfigEntity(
 *   id = "cloner_example_image_style",
 *   label = @Translation("Clone image style")
 * )
 */
final class ImageStyle extends ClonerConfigEntityClonePluginBase {

  /**
   * {@inheritdoc}
   */
  public function cloneEntity(EntityInterface $entity_source, EntityInterface $entity_destination, array $context = []): void {
    $id_key = $this->getDefinitionKey($entity_source, 'id');
    $label_key = $this->getDefinitionKey($entity_source, 'label');

    if (isset($context['form_state'])) {
      $form_state = $context['form_state'];
      \assert($form_state instanceof FormStateInterface);
      $destination_id = $form_state->getValue('machine_name');
      $label = $form_state->getValue('new_title');
    }
    else {
      $destination_id = $entity_source->id() . '_cloned';
      $label = $entity_source->label();
    }

    $entity_destination->set($id_key, $destination_id);
    $entity_destination->set($label_key, $label);
  }

}
