<?php

/**
 * @author:  Gabriel BONDAZ <gabriel.bondaz@idci-consulting.fr>
 * @license: MIT
 */

namespace IDCI\Bundle\ExtraFormBundle\Configuration\Builder;

use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use IDCI\Bundle\ExtraFormBundle\Configuration\Fetcher\ConfigurationFetcherRegistry;
use IDCI\Bundle\ExtraFormBundle\Configuration\Fetcher\ConfigurationFetcherInterface;
use IDCI\Bundle\ExtraFormBundle\Type\ExtraFormTypeRegistryInterface;
use IDCI\Bundle\ExtraFormBundle\Constraint\ExtraFormConstraintRegistryInterface;

class ExtraFormBuilder implements ExtraFormBuilderInterface
{
    /**
     * @var FormFactoryInterface
     */
    protected $formFactory;

    /**
     * @var ConfigurationFetcherRegistry
     */
    protected $configurationFetcherRegistry;

    /**
     * @var ExtraFormTypeRegistryInterface
     */
    protected $typeRegistry;

    /**
     * @var ExtraFormConstraintRegistryInterface
     */
    protected $constraintRegistry;

    /**
     * Constructor.
     *
     * @param FormFactoryInterface                 $formFactory                  the form factory
     * @param ConfigurationFetcherRegistry         $configurationFetcherRegistry the configuration fetcher registry
     * @param ExtraFormTypeRegistryInterface       $typeRegistry                 the type registry
     * @param ExtraFormConstraintRegistryInterface $constraintRegistry           the constraint registry
     */
    public function __construct(
        FormFactoryInterface                 $formFactory,
        ConfigurationFetcherRegistry         $configurationFetcherRegistry,
        ExtraFormTypeRegistryInterface       $typeRegistry,
        ExtraFormConstraintRegistryInterface $constraintRegistry
    ) {
        $this->formFactory = $formFactory;
        $this->configurationFetcherRegistry = $configurationFetcherRegistry;
        $this->typeRegistry = $typeRegistry;
        $this->constraintRegistry = $constraintRegistry;
    }

    /**
     * Define the field configuration using the option resolver component.
     *
     * @param OptionsResolver $resolver
     */
    protected function configureField(OptionsResolver $resolver)
    {
        $resolver
            ->setDefaults(array(
                'extra_form_type' => 'text',
                'options' => array(),
                'constraints' => array(),
            ))
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function build(
        $configuration,
        array $parameters = array(),
        $data = null,
        FormBuilderInterface $formBuilder = null
    ) {
        if (null === $formBuilder) {
            $formBuilder = $this->formFactory->createBuilder();
        }

        if (is_string($configuration)) {
            $configuration = $this
                ->configurationFetcherRegistry
                ->getFetcher($configuration)
            ;
        }

        if ($configuration instanceof ConfigurationFetcherInterface) {
            $configuration = $configuration->fetch($parameters);
        }

        if (is_array($configuration)) {
            foreach ($configuration as $name => $field) {
                $resolver = new OptionsResolver();
                $this->configureField($resolver);
                $resolvedField = $resolver->resolve($field);

                $formBuilder->add(
                    $name,
                    $this->buildFormType($resolvedField),
                    $this->buildFormOptions($name, $resolvedField, $data)
                );
            }
        }

        return $formBuilder;
    }

    /**
     * Build form type.
     *
     * @param array $field
     *
     * @return string
     */
    protected function buildFormType(array $field)
    {
        $extraFormType = $this
            ->typeRegistry
            ->getType($field['extra_form_type'])
        ;

        return $extraFormType->getFormType();
    }

    /**
     * Build constraint.
     *
     * @param array $constraint
     *
     * @return Symfony\Component\Validator\Constraint
     */
    protected function buildConstraint(array $constraint)
    {
        $extraFormConstraint = $this
            ->constraintRegistry
            ->getConstraint($constraint['extra_form_constraint'])
        ;

        $className = $extraFormConstraint->getClassName();
        $options = isset($constraint['options']) ? $constraint['options'] : array();

        return new $className($options);
    }

    /**
     * Build form options.
     *
     * @param string     $name
     * @param array      $field
     * @param array|null $data
     *
     * @return array
     */
    protected function buildFormOptions($name, array $field, $data = null)
    {
        // Allow sub options structure (collection case)
        if (isset($field['options']['constraints'])) {
            $field['options']['options'] = $this->buildFormOptions('', $field['options']);
        }

        $constraints = array();
        foreach ($field['constraints'] as $constraint) {
            $constraints[] = $this->buildConstraint($constraint);
        }

        $field['options']['constraints'] = $constraints;

        if (null !== $data && isset($data[$name])) {
            $field['options']['data'] = $data[$name];
        }

        return $field['options'];
    }
}
