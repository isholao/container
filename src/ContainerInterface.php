<?php

namespace Isholao\Container;

/**
 * @author Ishola O <ishola.tolu@outlook.com>
 */
interface ContainerInterface extends \ArrayAccess, \Psr\Container\ContainerInterface
{

    public function protect(string $key, $value);

    public function set(string $name, $value, bool $protect = FALSE);

    public function get($name);
    
    public function has($name): bool;
    
    public function remove(string $name): void;
}
