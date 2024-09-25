<?php

namespace App\Transformer;

use App\Entity\Tag;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\DataTransformerInterface;

class TagsTransformer implements DataTransformerInterface
{
    public function __construct(private readonly EntityManagerInterface $entityManager)
    {
    }

    public function transform($value): string
    {
        if (!$value instanceof ArrayCollection) {
            return '';
        }

        return implode(',', $value->map(fn(Tag $tag) => $tag->getName())->toArray());
    }

    public function reverseTransform($value): ArrayCollection
    {
        if (!$value) {
            return new ArrayCollection();
        }

        $tagNames = array_map('trim', explode(',', $value));
        $tags = new ArrayCollection();

        foreach ($tagNames as $tagName) {
            $tag = $this->entityManager->getRepository(Tag::class)
                ->findOneBy(['name' => $tagName]);

            if (!$tag instanceof Tag) {
                $tag = new Tag();
                $tag->setName($tagName);
            }

            $tags->add($tag);
        }

        return $tags;
    }
}
