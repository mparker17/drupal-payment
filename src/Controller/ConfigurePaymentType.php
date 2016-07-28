<?php

namespace Drupal\payment\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Form\FormBuilderInterface;
use Drupal\Core\StringTranslation\TranslationInterface;
use Drupal\payment\Plugin\Payment\Type\PaymentTypeDefinition;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Handles the "configure payment type" route.
 */
class ConfigurePaymentType extends ControllerBase {

  /**
   * Constructs a new instance.
   *
   * @param \Drupal\Core\Form\FormBuilderInterface $form_builder
   *   The form builder.
   * @param \Drupal\Core\StringTranslation\TranslationInterface $string_translation
   */
  public function __construct(FormBuilderInterface $form_builder, TranslationInterface $string_translation) {
    $this->formBuilder = $form_builder;
    $this->stringTranslation = $string_translation;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static($container->get('form_builder'), $container->get('string_translation'));
  }

  /**
   * Builds the payment type's configuration form.
   *
   * @param \Drupal\payment\Plugin\Payment\Type\PaymentTypeDefinition $payment_type_definition
   *
   * @return array
   *   A renderable array.
   */
  public function execute(PaymentTypeDefinition $payment_type_definition) {
    if ($payment_type_definition->getConfigurationFormClass()) {
      return $this->formBuilder->getForm($payment_type_definition->getConfigurationFormClass());
    }
    else {
      return [
        '#markup' => $this->t('This payment type has no configuration.'),
      ];
    }
  }

  /**
   * Gets the title of the payment type configuration page.
   *
   * @param \Drupal\payment\Plugin\Payment\Type\PaymentTypeDefinition $payment_type_definition
   *
   * @return string
   */
  public function title(PaymentTypeDefinition $payment_type_definition) {
    return $payment_type_definition->getLabel();
  }

}
