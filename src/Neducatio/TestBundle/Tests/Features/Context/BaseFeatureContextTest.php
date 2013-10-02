<?php
namespace Neducatio\TestBundle\Tests\Features\Context;

use Neducatio\TestBundle\Tests\Features\Context\FakeContext\TestableBaseFeatureContext;
use Mockery as m;

/**
 * Tests
 *
 * @covers Neducatio\TestBundle\Features\Context\BaseFeatureContext
 */
class BaseFeatureContextTest extends \PHPUnit_Framework_TestCase
{
  private $feature;

  /**
   * Sets up
   */
  public function setUp()
  {
    $this->feature = new TestableBaseFeatureContext(array());
  }

  /**
   * Tear down
   */
  public function tearDown()
  {
    m::close();
  }

  /**
   * Do sth.
   *
   * @test
   */
  public function __construct_shouldCreateInstanceOf()
  {
    $this->assertInstanceOf('Neducatio\TestBundle\Features\Context\BaseFeatureContext', $this->feature);
  }

  /**
   * Do sth.
   *
   * @test
   */
  public function setKernel_calledWithMock_shouldSetMockAsKernel()
  {
    $this->feature->setKernel($this->getKernelMock());
    $this->assertInstanceOf('Symfony\Component\HttpKernel\KernelInterface', $this->feature->kernel);
    $this->assertInstanceOf('Behat\Symfony2Extension\Context\KernelAwareInterface', $this->feature);
  }

  /**
   * Do sth.
   *
   * @group integration
   * @test
   */
  public function loadFixtures_someFixturesPassed_shouldThrowException()
  {
    $fixtures = array(
      \Neducatio\TestBundle\Tests\DataFixtures\FakeFixture\TestableFixture::NAME,
    );
    $this->feature->loadFixtures($fixtures);
  }

  /**
   * @test
   *
   * @expectedException RuntimeException
   * @expectedExceptionMessage Fixtures are not loaded
   */
  public function getReference_fixturesNotLoaded_shouldThrowRuntimeException()
  {
    $this->feature->getReference('ref');
  }

  /**
   * @test
   *
   * @expectedException PHPUnit_Framework_Error_Notice
   * @expectedExceptionMessage Undefined index: ref
   */
  public function getReference_fixtureLoaded_shouldReturnReferenceToThatFixture()
  {
    $fixtures = array(
      \Neducatio\TestBundle\Tests\DataFixtures\FakeFixture\TestableFixture::NAME,
    );
    $this->feature->loadFixtures($fixtures);
    $this->feature->getReference('ref');
  }

  /**
   * Do sth.
   *
   * @test
   */
  public function usingJs_called_shouldReturnFalse()
  {
    $this->assertFalse($this->feature->usingJs());
  }

  /**
   * Do sth.
   *
   * @test
   */
  public function usingJs_calledAfterEnablingJs_shouldReturnTrue()
  {
    $this->feature->enableJs();
    $this->assertTrue($this->feature->usingJs());
  }

  private function getKernelMock()
  {
    $container = m::mock('stdClass');
    $kernel = m::mock('Symfony\Component\HttpKernel\KernelInterface');
    $kernel->shouldReceive('getContainer')->andReturn($container);

    return $kernel;
  }
}
