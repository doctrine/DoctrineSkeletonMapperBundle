<?php

declare(strict_types=1);

namespace Doctrine\Bundle\DoctrineSkeletonMapperBundle;

use Doctrine\Common\EventManager;
use Doctrine\SkeletonMapper\DataSource\DataSource;
use Doctrine\SkeletonMapper\DataSource\DataSourceObjectDataRepository;
use Doctrine\SkeletonMapper\Hydrator\BasicObjectHydrator;
use Doctrine\SkeletonMapper\ObjectFactory;
use Doctrine\SkeletonMapper\ObjectManager;
use Doctrine\SkeletonMapper\ObjectRepository\ObjectRepositoryFactory as BaseObjectRepositoryFactory;
use Doctrine\SkeletonMapper\ObjectRepository\ObjectRepositoryInterface;

class ObjectRepositoryFactory
{
    /** @var ObjectManager */
    private $objectManager;

    /** @var ObjectFactory */
    private $objectFactory;

    /** @var BaseObjectRepositoryFactory */
    private $objectRepositoryFactory;

    /** @var EventManager */
    private $eventManager;

    public function __construct(
        ObjectManager $objectManager,
        ObjectFactory $objectFactory,
        BaseObjectRepositoryFactory $objectRepositoryFactory,
        EventManager $eventManager
    ) {
        $this->objectManager           = $objectManager;
        $this->objectFactory           = $objectFactory;
        $this->objectRepositoryFactory = $objectRepositoryFactory;
        $this->eventManager            = $eventManager;
    }

    public function createRepository(
        DataSource $dataSource,
        string $repositoryClassName,
        string $modelClassName
    ) : ObjectRepositoryInterface {
        $objectRepository = new $repositoryClassName(
            $this->objectManager,
            new DataSourceObjectDataRepository(
                $this->objectManager,
                $dataSource,
                $modelClassName
            ),
            $this->objectFactory,
            new BasicObjectHydrator($this->objectManager),
            $this->eventManager,
            $modelClassName
        );

        $this->objectRepositoryFactory->addObjectRepository($modelClassName, $objectRepository);

        return $objectRepository;
    }
}
