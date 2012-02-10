<?php

/**
 * @file
 * Hook documentation.
 */

/**
 * Defines payment statuses.
 *
 * @return array
 *   An array with PaymentStatusInfo objects.
 */
function hook_payment_status_info() {
  return array(
    new PaymentStatusInfo(array(
      'description' => t('Foo payments are still being processed by Bar to guarantee their authenticity.'),
      'status' => PAYMENT_STATUS_FOO,
      'parents' => array(PAYMENT_STATUS_PENDING),
      'title' => t('Pending (waiting for Bar authentication)'),
    )),
  );
}

/**
 * Defines payment method controllers.
 *
 * @return array
 *   An array with the names of payment method controller classes.
 */
function hook_payment_method_controller_info() {
  return array(
    'DummyPaymentMethodController',
    'CashOnDeliveryPaymentMethodController',
  );
}

/**
 * Executes when a payment status has been changed.
 *
 * @see Payment::setStatus()
 *
 * @param $payment Payment
 * @param $old_status string
 *   The status the payment had before it was changed.
 *
 * @return NULL
 */
function hook_payment_status_change(Payment $payment, $old_status) {
  // Notify the site administrator, for instance.
}

/**
 * Executes right before a payment is executed. This is the place to
 * programmatically alter payments.
 *
 * @see Payment::execute()
 *
 * @param $payment Payment
 *
 * @return NULL
 */
function hook_payment_pre_execute(Payment $payment) {
  // Add a payment method processing fee.
  $payment->amount += 5.50;
}