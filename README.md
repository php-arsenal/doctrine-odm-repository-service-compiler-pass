# doctrine-odm-repository-service-compiler-pass

Autoconfigure Doctrine ODM document repositories in Symfony as services to make them injectable into classes without the need to declare them in `services.yaml`.

`composer require php-arsenal/doctrine-odm-repository-service-compiler-pass`

[![Release](https://img.shields.io/github/v/release/php-arsenal/doctrine-odm-repository-service-compiler-pass)](https://github.com/php-arsenal/doctrine-odm-repository-service-compiler-pass/releases)
[![CI](https://img.shields.io/github/workflow/status/php-arsenal/doctrine-odm-repository-service-compiler-pass/CI)](https://github.com/php-arsenal/doctrine-odm-repository-service-compiler-pass/actions/workflows/ci.yml)
[![Packagist](https://img.shields.io/packagist/dt/php-arsenal/doctrine-odm-repository-service-compiler-pass)](https://packagist.org/packages/php-arsenal/doctrine-odm-repository-service-compiler-pass)

## How to use?

Update your `Kernel` class to add this compiler pass.

```php

use PhpArsenal\DoctrineODMRepositoryServiceCompilerPass\DocumentRepositoryAutoconfigureCompilerPass;

class Kernel extends BaseKernel
{
    use MicroKernelTrait;
    
    ...
    
    protected function build(ContainerBuilder $container): void
    {
        $container->addCompilerPass(new DocumentRepositoryAutoconfigureCompilerPass());
    }
    
    ...   
}
```

You can also reach defined document classes through parameter `doctrine_mongodb.mongodb.odm.document_classes`