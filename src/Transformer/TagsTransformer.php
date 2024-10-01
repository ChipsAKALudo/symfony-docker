<?php

namespace App\Transformer;

use App\Entity\Tag;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\DataTransformerInterface;

class TagsTransformer implements DataTransformerInterface
{
    public function __construct(private EntityManagerInterface $entityManager)
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
        if (empty($value)) {
            return new ArrayCollection();
        }

        $tagNames = array_unique(array_map('trim', explode(',', $value)));
        $existingTags = $this->entityManager->getRepository(Tag::class)->findBy(['name' => $tagNames]);
        $existingTagNames = array_map(static fn($tag) => $tag->getName(), $existingTags);

        $tags = new ArrayCollection($existingTags); // On fait une collection avec les tags qui existent déjà

        foreach ($tagNames as $tagName) {
            if (!in_array($tagName, $existingTagNames)) { // Si le tag n'existe pas, on le crée
                $tag = new Tag();
                $tag->setName($tagName);
                $tags->add($tag);
            }
        }

        return $tags;
    }
}
