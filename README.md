# doctrine-odm-repository-service-compiler-pass

Autoconfigure Doctrine ODM document repositories in Symfony as services to make them injectable into classes without the need to declare them in `services.yaml`.

`composer require php-arsenal/doctrine-odm-repository-service-compiler-pass`

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
