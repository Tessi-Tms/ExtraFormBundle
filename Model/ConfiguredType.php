<?php

namespace IDCI\Bundle\ExtraFormBundle\Model;

use IDCI\Bundle\ExtraFormBundle\Type\ExtraFormTypeInterface;

class ConfiguredType implements ExtraFormTypeInterface
{
    /**
     * @var mixed
     */
    protected $id;

    /**
     * @var string
     */
    protected $blockPrefix;

    /**
     * @var string
     */
    protected $description;

    /**
     * @var string
     */
    protected $tags;

    /**
     * @var string
     */
    protected $configuration;

    /**
     *  @var ExtraFormTypeInterface
     */
    protected $extraFormType;

    /**
     * Constructor.
     *
     * @param string $blockPrefix
     * @param string $configuration
     */
    public function __construct($blockPrefix = null, $configuration = null, $tags = null)
    {
        $this->blockPrefix = $blockPrefix;
        $this->configuration = $configuration;
        if (!empty($tags)) {
            $this->tags = $tags;
        }
    }

    /**
     * To string.
     *
     * @return string
     */
    public function __toString()
    {
        return $this->getBlockPrefix();
    }

    /**
     * Returns the id.
     *
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set blockPrefix.
     *
     * @param string $blockPrefix
     *
     * @return ConfiguredType
     */
    public function setBlockPrefix($blockPrefix)
    {
        $this->blockPrefix = $blockPrefix;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return $this->blockPrefix;
    }

    /**
     * {@inheritdoc}
     */
    public function getFormTypeName()
    {
        if (null === $this->extraFormType) {
            return null;
        }

        return $this->extraFormType->getBlockPrefix();
    }

    /**
     * {@inheritdoc}
     */
    public function getFormType()
    {
        if (null === $this->extraFormType) {
            return null;
        }

        return $this->extraFormType->getFormType();
    }

    /**
     * {@inheritdoc}
     */
    public function getParent()
    {
        if (null === $this->extraFormType) {
            return null;
        }

        return $this->extraFormType->getParent();
    }

    /**
     * Set description.
     *
     * @param string $description
     *
     * @return ConfiguredType
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getDescription()
    {
        if (null !== $this->description) {
            return $this->description;
        }

        if (null !== $this->extraFormType) {
            return $this->extraFormType->getDescription();
        }

        return null;
    }

    /**
     * {@inheritdoc}
     */
    public function getIcon()
    {
        if (null === $this->extraFormType) {
            return null;
        }

        return $this->extraFormType->getIcon();
    }

    /**
     * {@inheritdoc}
     */
    public function isAbstract()
    {
        if (null === $this->extraFormType) {
            return null;
        }

        return $this->extraFormType->isAbstract();
    }

    /**
     * {@inheritdoc}
     */
    public function getExtraFormOptions()
    {
        if (null === $this->extraFormType) {
            return null;
        }

        $configurationArray = json_decode($this->configuration, true);
        $options = $this->extraFormType->getExtraFormOptions();

        foreach ($configurationArray['extra_form_options'] as $optionName => $optionValue) {
            if ($optionName === 'configuration') {
                $options[$optionName]['options']['data'] = json_encode($optionValue);
            } else {
                $options[$optionName]['options']['data'] = $optionValue;
            }
        }

        return $options;
    }

    /**
     * Set tags.
     *
     * @param string $tags
     *
     * @return ConfiguredType
     */
    public function setTags($tags)
    {
        $this->tags = $tags;

        return $this;
    }

    /**
     * Get tags.
     *
     * @return string
     */
    public function getTags()
    {
        return $this->tags;
    }

    /**
     * Set configuration.
     *
     * @param string $configuration
     *
     * @return ConfiguredType
     */
    public function setConfiguration($configuration)
    {
        $this->configuration = $configuration;

        return $this;
    }

    /**
     * Get configuration.
     *
     * @return string
     */
    public function getConfiguration()
    {
        return $this->configuration;
    }

    /**
     * Set extraFormType.
     *
     * @param ExtraFormTypeInterface $extraFormType
     *
     * @return ConfiguredType
     */
    public function setExtraFormType(ExtraFormTypeInterface $extraFormType)
    {
        $this->extraFormType = $extraFormType;

        return $this;
    }

    /**
     * Get extraFormType.
     *
     * @return ExtraFormTypeInterface
     */
    public function getExtraFormType()
    {
        return $this->extraFormType;
    }

    /**
     * Get extra form constraints.
     *
     * @return array
     */
    public function getExtraFormConstraints()
    {
        if (null === $this->extraFormType) {
            return null;
        }

        $configurationArray = json_decode($this->configuration, true);

        return $configurationArray['extra_form_constraints'];
    }
}
