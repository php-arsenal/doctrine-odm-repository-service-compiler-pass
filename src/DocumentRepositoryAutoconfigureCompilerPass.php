<?php

namespace PhpArsenal\DoctrineODMRepositoryServiceCompilerPass;


use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\Common\Annotations\DocParser;
use Doctrine\ODM\MongoDB\Mapping\Annotations\Document;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;

class DocumentRepositoryAutoconfigureCompilerPass implements CompilerPassInterface
{
    private AnnotationReader $annotationReader;

    public function __construct()
    {
        $this->annotationReader = new AnnotationReader(new DocParser());
    }

    public function process(ContainerBuilder $container)
    {
        foreach ($this->getFullyQualifiedDocumentClassNames($container) as $documentClass) {
            $reflectionClass = $container->getReflectionClass($documentClass);

            if (!$reflectionClass || $reflectionClass->isAbstract()) {
                continue;
            }

            /** @var Document $documentAnnotation */
            $documentAnnotation = $this->annotationReader->getClassAnnotation($reflectionClass, Document::class);
            if (!$documentAnnotation) {
                continue;
            }
            $documentRepositoryClass = $documentAnnotation->repositoryClass;

            if (!in_array($documentRepositoryClass, $container->getServiceIds()) && class_exists($documentRepositoryClass)) {
                $this->addDocumentRepositoryService($container, $documentClass, $documentRepositoryClass);
            }
        }
    }

    private function getFullyQualifiedDocumentClassNames(ContainerBuilder $container): array
    {
        $doctrineConfig = array_merge(...$container->getExtensionConfig('doctrine_mongodb'));
        $documentDirs = array_map(function (array $mappingConfig) use ($container) {
            return $container->getParameterBag()->resolveValue($mappingConfig['dir']);
        }, $doctrineConfig['document_managers']['default']['mappings']);

        $documentClasses = [];
        foreach ($documentDirs as $documentDir) {
            $documentClasses = array_merge($documentClasses, FullyQualifiedClassNameReader::readAll($documentDir));
        }

        return $documentClasses;
    }

    private function addDocumentRepositoryService(ContainerBuilder $container, string $documentClass, string $documentRepositoryClass): void
    {
        $repositoryServiceDefinition = new Definition($documentRepositoryClass);
        $repositoryServiceDefinition->setFactory([new Reference('doctrine_mongodb.odm.document_manager'), 'getRepository']);
        $repositoryServiceDefinition->addArgument($documentClass);
        $repositoryServiceDefinition->setAutowired(true);
        $repositoryServiceDefinition->setAutoconfigured(true);

        $container->setDefinition($documentRepositoryClass, $repositoryServiceDefinition);
    }
}
