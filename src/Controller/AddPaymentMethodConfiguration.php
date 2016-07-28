<?php

namespace Drupal\payment\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Entity\EntityAccessControlHandlerInterface;
use Drupal\Core\Entity\EntityFormBuilderInterface;
use Drupal\Core\Entity\EntityStorageInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\StringTranslation\TranslationInterface;
use Drupal\plugin\PluginDefinition\PluginDefinitionInterface;
use Drupal\plugin\PluginDefinition\PluginLabelDefinitionInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Handles the "add payment method configuration" route.
 */
class AddPaymentMethodConfiguration extends ControllerBase {

  /**
   * The payment method configuration access control handler.
   *
   * @var \Drupal\Core\Entity\EntityAccessControlHandlerInterface
   */
  protected $paymentMethodConfigurationAccessControlHandler;

  /**
   * The payment method configuration storage.
   *
   * @var \Drupal\Core\Entity\EntityStorageInterface
   */
  protected $paymentMethodConfigurationStorage;

  /**
   * Constructs a new instance.
   *
   * @param \Drupal\Core\StringTranslation\TranslationInterface $string_translation
   *   The string translator.
   * @param \Drupal\Core\Entity\EntityFormBuilderInterface $entity_form_builder
   * @param \Drupal\Core\Session\AccountInterface $current_user
   * @param \Drupal\Core\Entity\EntityStorageInterface $payment_method_configuration_storage
   *   The payment method configuration storage.
   * @param \Drupal\Core\Entity\EntityAccessControlHandlerInterface $payment_method_configuration_access_control_handler
   *   The payment method configuration access control handler.
   */
  public function __construct(TranslationInterface $string_translation, EntityFormBuilderInterface $entity_form_builder, AccountInterface $current_user, EntityStorageInterface $payment_method_configuration_storage, EntityAccessControlHandlerInterface $payment_method_configuration_access_control_handler) {
    $this->currentUser = $current_user;
    $this->entityFormBuilder = $entity_form_builder;
    $this->paymentMethodConfigurationAccessControlHandler = $payment_method_configuration_access_control_handler;
    $this->paymentMethodConfigurationStorage = $payment_method_configuration_storage;
    $this->stringTranslation = $string_translation;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    /** @var \Drupal\Core\Entity\EntityTypeManagerInterface $entity_type_manager */
    $entity_type_manager = $container->get('entity_type.manager');

    return new static($container->get('string_translation'), $container->get('entity.form_builder'), $container->get('current_user'), $entity_type_manager->getStorage('payment_method_configuration'), $entity_type_manager->getAccessControlHandler('payment_method_configuration'));
  }

  /**
   * Displays a payment method configuration add form.
   *
   * @param \Drupal\plugin\PluginDefinition\PluginDefinitionInterface $payment_method_configuration_definition
   *
   * @return array
   */
  public function execute(PluginDefinitionInterface $payment_method_configuration_definition) {
    $payment_method_configuration = $this->paymentMethodConfigurationStorage->create([
      'pluginId' => $payment_method_configuration_definition->getId(),
    ]);

    return $this->entityFormBuilder->getForm($payment_method_configuration, 'default');
  }

  /**
   * Returns the title for the payment method configuration add form.
   *
   * @param \Drupal\plugin\PluginDefinition\PluginLabelDefinitionInterface $payment_method_configuration_definition
   *
   * @return string
   */
  public function title(PluginLabelDefinitionInterface $payment_method_configuration_definition) {
    return $this->t('Add %label payment method configuration', [
      '%label' => $payment_method_configuration_definition->getLabel(),
    ]);
  }

  /**
   * Checks access to the route.
   *
   * @param \Drupal\plugin\PluginDefinition\PluginDefinitionInterface $payment_method_configuration_definition
   *
   * @return \Drupal\Core\Access\AccessResultInterface
   *   The access result.
   */
  public function access(PluginDefinitionInterface $payment_method_configuration_definition) {
    return $this->paymentMethodConfigurationAccessControlHandler->createAccess($payment_method_configuration_definition->getId(), $this->currentUser, [], TRUE);
  }

}
