<?php

namespace App\Tests\Service;

use App\Entity\AttributeCv;
use App\Entity\Position;
use App\Entity\PositionAttr;
use App\Repository\AttributeCvRepository;
use App\Service\PositionAttributeSynchronizer;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;

final class PositionAttributeSynchronizerTest extends TestCase
{
    public function testReplacesExistingPositionAttributesWithSubmittedAttributes(): void
    {
        $position = new Position();
        $oldPositionAttr = new PositionAttr();
        $position->addPositionAttr($oldPositionAttr);

        $firstAttribute = new AttributeCv();
        $secondAttribute = new AttributeCv();
        $attributeRepository = $this->createStub(AttributeCvRepository::class);
        $attributeRepository->method('findBy')->willReturn([$firstAttribute, $secondAttribute]);

        $createdPositionAttrs = [];
        $entityManager = $this->createMock(EntityManagerInterface::class);
        $entityManager->expects($this->exactly(2))
            ->method('persist')
            ->willReturnCallback(static function (object $entity) use (&$createdPositionAttrs): void {
                $createdPositionAttrs[] = $entity;
            });

        $synchronizer = new PositionAttributeSynchronizer($attributeRepository, $entityManager);
        $synchronizer->sync($position, [1, 2]);

        self::assertCount(2, $position->getPositionAttrs());
        self::assertNull($oldPositionAttr->getPosition());
        self::assertSame($position, $createdPositionAttrs[0]->getPosition());
        self::assertSame($firstAttribute, $createdPositionAttrs[0]->getAttribute());
        self::assertSame($secondAttribute, $createdPositionAttrs[1]->getAttribute());
    }
}
