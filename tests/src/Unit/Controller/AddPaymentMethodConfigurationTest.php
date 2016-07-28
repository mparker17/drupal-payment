<?php

namespace Drupal\Tests\payment\Unit\Controller;

use Drupal\Core\Access\AccessResult;
use Drupal\Core\Entity\EntityAccessControlHandlerInterface;
use Drupal\Core\Entity\EntityFormBuilderInterface;
use Drupal\Core\Entity\EntityStorageInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\StringTranslation\TranslatableMarkup;
use Drupal\payment\Controller\AddPaymentMethodConfiguration;
use Drupal\payment\Entity\PaymentMethodConfigurationInterface;
use Drupal\plugin\PluginDefinition\PluginDefinitionInterface;
use Drupal\plugin\PluginDefinition\PluginLabelDefinitionInterface;
use Drupal\Tests\UnitTestCase;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * @coversDefaultClass \Drupal\payment\Controller\AddPaymentMethodConfiguration
 *
 * @group Payment
 */
class AddPaymentMethodConfigurationTest extends UnitTestCase {

  /**
   * The current user.
   *
   * @var \Drupal\Core\Session\AccountInterface|\PHPUnit_Framework_MockObject_MockObject
   */
  protected $currentUser;

  /**
   * The entity form builder.
   *
   * @var \Drupal\Core\Entity\EntityFormBuilderInterface|\Prophecy\Prophecy\ObjectProphecy
   */
  protected $entityFormBuilder;

  /**
   * The payment method configuration access control handler.
   *
   * @var \Drupal\Core\Entity\EntityAccessControlHandlerInterface|\Prophecy\Prophecy\ObjectProphecy
   */
  protected $paymentMethodConfigurationAccessControlHandler;

  /**
   * The payment method configuration storage.
   *
   * @var \Drupal\Core\Entity\EntityStorageInterface|\Prophecy\Prophecy\ObjectProphecy
   */
  protected $paymentMethodConfigurationStorage;

  /**
   * The string translator.
   *
   * @var \Drupal\Core\StringTranslation\TranslationInterface|\PHPUnit_Framework_MockObject_MockObject
   */
  protected $stringTranslation;

  /**
   * The class under test.
   *
   * @var \Drupal\payment\Controller\AddPaymentMethodConfiguration
   */
  protected $sut;

  /**
   * {@inheritdoc}
   */
  protected function setUp() {
    $this->currentUser = $this->getMock(AccountInterface::class);

    $this->entityFormBuilder = $this->prophesize(EntityFormBuilderInterface::class);

    $this->paymentMethodConfigurationAccessControlHandler = $this->prophesize(EntityAccessControlHandlerInterface::class);

    $this->paymentMethodConfigurationStorage = $this->prophesize(EntityStorageInterface::class);

    $this->stringTranslation = $this->getStringTranslationStub();

    $this->sut = new AddPaymentMethodConfiguration($this->stringTranslation, $this->entityFormBuilder->reveal(), $this->currentUser, $this->paymentMethodConfigurationStorage->reveal(), $this->paymentMethodConfigurationAccessControlHandler->reveal());
  }

  /**
   * @covers ::create
   * @covers ::__construct
   */
  function testCreate() {
    $entity_type_manager = $this->prophesize(EntityTypeManagerInterface::class);
    $entity_type_manager->getAccessControlHandler('payment_method_configuration')->willReturn($this->paymentMethodConfigurationAccessControlHandler->reveal());
    $entity_type_manager->getStorage('payment_method_configuration')->willReturn($this->paymentMethodConfigurationStorage->reveal());

    $container = $this->prophesize(ContainerInterface::class);
    $container->get('current_user')->willReturn($this->currentUser);
    $container->get('entity.form_builder')->willReturn($this->entityFormBuilder->reveal());
    $container->get('entity_type.manager')->willReturn($entity_type_manager);
    $container->get('string_translation')->willReturn($this->stringTranslation);

    $sut = AddPaymentMethodConfiguration::create($container->reveal());
    $this->assertInstanceOf(AddPaymentMethodConfiguration::class, $sut);
  }

  /**
   * @covers ::execute
   */
  public function testExecute() {
    $payment_method_configuration_id = 'foo:bar';
    $payment_method_configuration_definition = $this->prophesize(PluginDefinitionInterface::class);
    $payment_method_configuration_definition->getId()->willReturn($payment_method_configuration_id);

    $payment_method_configuration = $this->getMock(PaymentMethodConfigurationInterface::class);

    $this->paymentMethodConfigurationStorage->create([
      'pluginId' => $payment_method_configuration_id,
    ])
      ->willReturn($payment_method_configuration);

    $form = [
      '#type' => 'foo_bar',
    ];

    $this->entityFormBuilder->getForm($payment_method_configuration, 'default')
      ->willReturn($form);

    $this->assertSame($form, $this->sut->execute($payment_method_configuration_definition->reveal()));
  }

  /**
   * @covers ::access
   */
  public function testAccessWithAllowed() {
    $payment_method_configuration_id = 'foo:bar';
    $payment_method_configuration_definition = $this->prophesize(PluginDefinitionInterface::class);
    $payment_method_configuration_definition->getId()->willReturn($payment_method_configuration_id);

    $this->paymentMethodConfigurationAccessControlHandler->createAccess($payment_method_configuration_id, $this->currentUser, [], TRUE)
      ->willReturn(AccessResult::allowed());

    $this->assertTrue($this->sut->access($payment_method_configuration_definition->reveal())->isAllowed());
  }

  /**
   * @covers ::access
   */
  public function testAccessWithForbidden() {
    $payment_method_configuration_id = 'foo:bar';
    $payment_method_configuration_definition = $this->prophesize(PluginDefinitionInterface::class);
    $payment_method_configuration_definition->getId()->willReturn($payment_method_configuration_id);

    $this->paymentMethodConfigurationAccessControlHandler->createAccess($payment_method_configuration_id, $this->currentUser, [], TRUE)
      ->willReturn(AccessResult::forbidden());

    $this->assertTrue($this->sut->access($payment_method_configuration_definition->reveal())->isForbidden());
  }

  /**
   * @covers ::title
   */
  public function testTitle() {
    $payment_method_configuration_label = 'Foo Bar';
    $payment_method_configuration_definition = $this->prophesize(PluginLabelDefinitionInterface::class);
    $payment_method_configuration_definition->getLabel()->willReturn($payment_method_configuration_label);

    $this->assertInstanceOf(TranslatableMarkup::class, $this->sut->title($payment_method_configuration_definition->reveal()));
  }

}
