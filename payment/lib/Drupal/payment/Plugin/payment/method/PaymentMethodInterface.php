<?php

/**
 * Contains \Drupal\payment\Plugin\payment\method\PaymentMethodInterface.
 */

namespace Drupal\payment\Plugin\payment\method;

use Drupal\Component\Plugin\PluginInspectionInterface;
use Drupal\payment\PaymentProcessingInterface;
use Drupal\payment\Entity\PaymentMethodInterface as EntityPaymentMethodInterface;

/**
 * A payment method plugin (the logic behind a payment method entity).
 *
 * @see \Drupal\payment\Entity\PaymentMethod
 */
interface PaymentMethodInterface extends PaymentProcessingInterface, PluginInspectionInterface {

  /**
   * Gets the plugin configuration.
   *
   * @return array
   *  The data is not allowed to contain objects.
   */
  public function getConfiguration();

  /**
   * sets the payment method the plugin instance is for.
   *
   * @param \Drupal\payment\Entity\PaymentMethodInterface $payment_method
   *
   * @return \Drupal\payment\Plugin\payment\method\PaymentMethodInterface
   */
  public function setPaymentMethod(EntityPaymentMethodInterface $payment_method);

  /**
   * Gets the payment method the plugin instance is for.
   *
   * @return \Drupal\payment\Entity\PaymentMethodInterface
   */
  public function getPaymentMethod();

  /**
   * Returns the form elements to configure payment methods.
   *
   * $form_state['payment_method'] contains the payment method that is added or
   * edited. All method-specific information should be added to it during
   * element validation. The payment method will be saved automatically.
   *
   * @param array $form
   * @param array $form_state
   *
   * @return array
   *   A render array.
   */
  public function paymentMethodFormElements(array $form, array &$form_state);
}
