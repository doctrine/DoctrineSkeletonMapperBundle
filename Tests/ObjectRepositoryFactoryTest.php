<?php

declare(strict_types=1);

namespace Doctrine\Bundle\DoctrineSkeletonMapperBundle\Tests;

use Doctrine\Bundle\DoctrineSkeletonMapperBundle\ObjectRepositoryFactory;
use Doctrine\Common\EventManager;
use Doctrine\SkeletonMapper\DataSource\DataSource;
use Doctrine\SkeletonMapper\ObjectFactory;
use Doctrine\SkeletonMapper\ObjectManager;
use Doctrine\SkeletonMapper\ObjectRepository\ObjectRepositoryFactory as BaseObjectRepositoryFactory;
use PHPUnit\Framework\TestCase;
use stdClass;

class ObjectRepositoryFactoryTest extends TestCase
{
    public function testCreateRepository() : void
    {
        $objectManager               = $this->createMock(ObjectManager::class);
        $objectFactory               = $this->createMock(ObjectFactory::class);
        $baseObjectRepositoryFactory = $this->createMock(BaseObjectRepositoryFactory::class);
        $eventManager                = $this->createMock(EventManager::class);

        $objectRepositoryFactory = new ObjectRepositoryFactory(
            $objectManager,
            $objectFactory,
            $baseObjectRepositoryFactory,
            $eventManager
        );

        $dataSource = new class() implements DataSource
        {
            /**
             * @return mixed[][]
             */
            public function getSourceRows() : array
            {
                return [
                    ['username' => 'jwage'],
                ];
            }
        };

        $baseObjectRepositoryFactory->expects(self::once())
            ->method('addObjectRepository')
            ->with(stdClass::class);

        $objectRepository = $objectRepositoryFactory->createRepository(
            $dataSource,
            TestRepository::class,
            stdClass::class
        );
    }
}
