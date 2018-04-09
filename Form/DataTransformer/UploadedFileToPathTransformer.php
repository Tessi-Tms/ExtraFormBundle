<?php

namespace IDCI\Bundle\ExtraFormBundle\Form\DataTransformer;

use Symfony\Component\Form\DataTransformerInterface;

class UploadedFileToPathTransformer implements DataTransformerInterface
{
    private $uploadsDir;

    public function __construct(string $uploadsDir)
    {
        $this->uploadsDir = $uploadsDir;
    }

    /**
     * {@inheritdoc}
     */
    public function transform($value)
    {
        return null;
    }

    /**
     * {@inheritdoc}
     */
    public function reverseTransform($uploadedFile)
    {
        $path = sprintf('%s/%s', $this->uploadsDir, (new \DateTime('now'))->format('Ymd'));
        $name = sprintf('%s-%s', uniqid(), $uploadedFile->getClientOriginalName());

        $uploadedFile->move($path, $name);

        return sprintf('%s/%s', $path, $name);
    }
}
