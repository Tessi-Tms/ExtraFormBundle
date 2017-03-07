<?php

/**
 * @author:  Baptiste BOUCHEREAU <baptiste.bouchereau@idci-consulting.fr>
 * @license: MIT
 */

namespace IDCI\Bundle\ExtraFormBundle\Tests\Type;

use IDCI\Bundle\ExtraFormBundle\Type\ExtraFormType;
use IDCI\Bundle\ExtraFormBundle\Type\ExtraFormTypeRegistry;

class ExtraFormTypeRegistryTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var ExtraFormType
     */
    private $extraFormType;

    /**
     * {@inheritDoc}
     */
    protected function setUp()
    {
        $configuration = array(
            'name'              => 'html',
            'description'        => 'Html text field',
            'icon' => 'code',
            'parent' => 'form',
            'abstract' => false,
            'form_type' => 'extra_form_html',
            'extra_form_options' => array(
                'content' => array(
                    'extra_form_type' => 'textarea',
                    'options' => array(
                        'required' => false
                    )
                ),
                'mapped' => array(
                    'extra_form_type' => 'checkbox',
                    'options' => array(
                        'required' => false,
                        'data'     => false
                    )
                )
            )
        );

        $this->extraFormType = new ExtraFormType($configuration);
    }

    /**
     * Test setType
     */
    public function testSetType()
    {
        $registry = new ExtraFormTypeRegistry();
        $this->assertEquals($registry->setType('html', $this->extraFormType), $registry);
        $this->assertArrayHasKey('html', $registry->getTypes());
    }

    /**
     * Test getTypes
     */
    public function testGetTypes()
    {
        $registry = new ExtraFormTypeRegistry();
        $registry->setType('html', $this->extraFormType);
        $this->assertArrayHasKey('html', $registry->getTypes());
        $this->assertEquals(1, count($registry->getTypes()));
    }

    /**
     * Test getType
     */
    public function testGetType()
    {
        $registry = new ExtraFormTypeRegistry();
        $registry->setType('html', $this->extraFormType);
        $this->assertNotEmpty($registry->getType('html'));

        $this->expectException('IDCI\Bundle\ExtraFormBundle\Exception\UnexpectedTypeException');
        $registry->getType(array());

        $this->expectException('IDCI\Bundle\ExtraFormBundle\Exception\InvalidArgumentException');
        $registry->getType('no_blank');
    }

    /**
     * Test hasType
     */
    public function testHasType()
    {
        $registry = new ExtraFormTypeRegistry();
        $registry->setType('html', $this->extraFormType);
        $this->assertTrue($registry->hasType('html'));
        $this->assertFalse($registry->hasType('html_'));
    }
}
