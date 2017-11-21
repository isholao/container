<?php

namespace Isholao\Container;

/**
 * @author Ishola O <ishola.tolu@outlook.com>
 */
class Container implements ContainerInterface
{

    private $data = [];
    private $private = [];
    private $closures = [];
    private $caches = [];

    function __construct(array $data = [])
    {
        foreach ($data as $key => $value)
        {
            $this->set((string) $key, $value);
        }
    }

    /**
     * Protect container item
     * 
     * @param string $key
     * @param mixed $value
     */
    public function protect(string $key, $value)
    {
        if (empty($key))
        {
            throw new \InvalidArgumentException('Key cannot be empty.');
        }
        return $this->set($key, $value, TRUE);
    }

    /**
     * Add a new container item
     * 
     * @param string $name string name of the item
     * @param mixed  $value any php value to be added
     * @param bool   $protect whether it should be protected or not
     * @throws \Error
     */
    public function set(string $name, $value, bool $protect = FALSE)
    {
        if (empty($name))
        {
            throw new \InvalidArgumentException('Container name cannot be empty.');
        }

        if (isset($this->private[$key = \strtolower($name)]))
        {
            throw new \InvalidArgumentException("'$key' already exists and protected.");
        }

        if ($value instanceof \Closure)
        {
            $this->closures[$key] = TRUE;
        }

        $this->data[$key] = $value;

        if ($protect)
        {
            $this->private[$key] = TRUE;
        }

        return $this;
    }

    public function __set(string $name, $value)
    {
        $this->set($name, $value);
    }

    public function __get(string $name)
    {
        return $this->get($name);
    }

    /**
     * Get a container item by name
     * 
     * @param string $name name of the container item
     * @return mixed
     */
    public function get($name)
    {
        if (empty($name))
        {
            throw new \InvalidArgumentException('Container name cannot be empty.');
        }

        $key = \strtolower($name);
        if (isset($this->data[$key]))
        {
            return $this->data[$key];
        }

        throw new \InvalidArgumentException("'$name' identifier cannot be found.");
    }

    /**
     * 
     * @param string $name
     * @param array $args
     * @return array
     * @throws \Exception
     */
    public function __call(string $name, array $args = [])
    {
        if (!isset($this->closures[$key = \strtolower($name)]))
        {
            throw new \Exception("Unable to call an unregistered function '$key'.");
        }

        $hash = \sha1(\var_export([$key, $name, $args], TRUE));

        if (isset($this->caches[$hash]))
        {
            return $this->caches[$hash];
        }

        $closure = $this->data[$key]->bindTo($this);
        return $this->caches[$hash] = \call_user_func_array($closure, $args);
    }

    /**
     * Does an item with this name exists in the container
     * 
     * @param string $name
     * @return bool
     */
    public function has($name): bool
    {
        if (empty($name))
        {
            throw new \InvalidArgumentException('Container name cannot be empty.');
        }
        return \array_key_exists(\strtolower($name), $this->data);
    }

    /**
     * Remove an item from the container by name
     * 
     * @param string $name
     */
    public function remove(string $name): void
    {
        if (empty($name))
        {
            throw new \InvalidArgumentException('Container name cannot be empty.');
        }
        $key = \strtolower($name);
        $this->data[$key] = NULL;
        $this->closures[$key] = NULL;
        $this->private[$key] = NULL;
        unset($this->data[$key], $this->closures[$key], $this->private[$key]);
    }

    public function offsetExists($offset): bool
    {
        return $this->has($offset);
    }

    public function offsetSet($offset, $value): void
    {
        $this->set($offset, $value);
    }

    public function offsetGet($offset)
    {
        return $this->get($offset);
    }

    public function offsetUnset($offset): void
    {
        $this->remove($offset);
    }

}
