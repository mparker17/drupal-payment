<?php

namespace Drupal\Tests\payment\Unit\Controller;

use Drupal\Core\Form\FormBuilderInterface;
use Drupal\payment\Controller\ConfigurePaymentType;
use Drupal\payment\Plugin\Payment\Type\PaymentTypeDefinition;
use Drupal\Tests\UnitTestCase;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * @coversDefaultClass \Drupal\payment\Controller\ConfigurePaymentType
 *
 * @group Payment
 */
class ConfigurePaymentTypeTest extends UnitTestCase {

  /**
   * The form builder.
   *
   * @var \Drupal\Core\Form\FormBuilderInterface|\Prophecy\Prophecy\ObjectProphecy
   */
  protected $formBuilder;

  /**
   * The string translator.
   *
   * @var \Drupal\Core\StringTranslation\TranslationInterface
   */
  protected $stringTranslation;

  /**
   * The class under test.
   *
   * @var \Drupal\payment\Controller\ConfigurePaymentType
   */
  protected $sut;

  /**
   * {@inheritdoc}
   */
  protected function setUp() {
    $this->formBuilder = $this->prophesize(FormBuilderInterface::class);

    $this->stringTranslation = $this->getStringTranslationStub();

    $this->sut = new ConfigurePaymentType($this->formBuilder->reveal(), $this->stringTranslation);
  }

  /**
   * @covers ::create
   * @covers ::__construct
   */
  function testCreate() {
    $container = $this->prophesize(ContainerInterface::class);
    $container->get('form_builder')->willReturn($this->formBuilder->reveal());
    $container->get('string_translation')->willReturn($this->stringTranslation);

    $sut = ConfigurePaymentType::create($container->reveal());
    $this->assertInstanceOf(ConfigurePaymentType::class, $sut);
  }

  /**
   * @covers ::execute
   */
  public function testExecuteWithoutConfigurationForm() {
    $payment_type_definition = $this->prophesize(PaymentTypeDefinition::class);
    $payment_type_definition->getConfigurationFormClass()->willReturn(NULL);

    $build = $this->sut->execute($payment_type_definition->reveal());
    $this->assertInternalType('array', $build);
  }

  /**
   * @covers ::execute
   */
  public function testExecute() {
    $configuration_form_class = $this->randomMachineName();
    $payment_type_definition = $this->prophesize(PaymentTypeDefinition::class);
    $payment_type_definition->getConfigurationFormClass()->willReturn($configuration_form_class);

    $form_build = [
      '#type' => $this->randomMachineName(),
    ];
    $this->formBuilder->getForm($configuration_form_class)
      ->willReturn($form_build);

    $build = $this->sut->execute($payment_type_definition->reveal());
    $this->assertSame($form_build, $build);
  }

  /**
   * @covers ::title
   */
  public function testTitle() {
    $label = $this->randomMachineName();
    $payment_type_definition = $this->prophesize(PaymentTypeDefinition::class);
    $payment_type_definition->getLabel()->willReturn($label);

    $this->assertSame($label, $this->sut->title($payment_type_definition->reveal()));
  }

}
