<?php

namespace WC_BE\Http\Utility\Registrar;





use WC_BE\Dependencies\Psr\Container\ContainerExceptionInterface;
use WC_BE\Dependencies\Psr\Container\ContainerInterface;
use WC_BE\Dependencies\Psr\Container\NotFoundExceptionInterface;

class ControllerRegistrar
{
    protected string $dirname;
    protected array $controllers = [];
    protected ContainerInterface $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
        $this->defaultPath();
        $this->readControllers();
    }

    private function defaultPath(): void
    {
        $this->dirname = str_replace('\\', '/', PS_QR_ACCESS_PLUGIN_DIR) . 'app/Http/Controllers';
    }

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function register(): void
    {
        foreach ($this->getControllers() as $controllerClass) {
            $controller = $this->container->get($controllerClass);
        }
        (new ScriptRegister())->enqueue('wc-beexpress','public/main.js');

    }

    public function getControllers(): array
    {
        return $this->controllers;
    }

    protected function readControllers(): void
    {
        if (is_dir($this->dirname)) {
            foreach (glob($this->dirname . '/*', GLOB_ONLYDIR) as $subDir) {
                $this->readDir($subDir);
            }
        }
    }

    protected function readDir(string $directory): void
    {
        if (is_dir($directory)) {
            foreach (glob($directory . '/*.php') as $file) {
                $name = basename($file, '.php');

                if (str_contains($name, "Abstract")) {
                    continue;
                }

                $relativePath = str_replace($this->dirname, '', $directory);
                $relativePath = trim($relativePath, '/');

                $namespace = 'WC_BE\Http\Controllers\\' . str_replace('/', '\\', $relativePath) . '\\' . $name;
                $this->controllers[] = $namespace;
            }
        }
    }
}
