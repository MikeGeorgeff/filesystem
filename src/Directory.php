<?php

namespace Georgeff\Filesystem;

use Symfony\Component\Finder\Finder;
use Georgeff\Filesystem\Exception\DirectoryNotFoundException;
use Georgeff\Filesystem\Exception\DirectoryExistsException;

class Directory
{
    /**
     * Make a new Directory instance
     *
     * @return \Georgeff\Filesystem\Directory
     */
    public static function make()
    {
        return new static();
    }

    /**
     * Determine if a directory exists
     *
     * @param string $path
     * @return bool
     */
    public function exists($path)
    {
        return is_dir($path);
    }

    /**
     * Create a new directory
     *
     * @param string $path
     * @param int $mode
     * @param bool|false $recursive
     * @return bool
     *
     * @throws \Georgeff\Filesystem\Exception\DirectoryExistsException
     */
    public function create($path, $mode = 0777, $recursive = false)
    {
        if ($this->exists($path)) {
            throw new DirectoryExistsException("The directory [$path] already exists.");
        }

        return mkdir($path, $mode, $recursive);
    }

    /**
     * Move a directory to a different location
     *
     * @param string $path
     * @param string $destination
     * @param null|int $options
     * @param bool|false $keep
     * @return bool
     *
     * @throws \Georgeff\Filesystem\Exception\DirectoryExistsException
     * @throws \Georgeff\Filesystem\Exception\DirectoryNotFoundException
     */
    public function move($path, $destination, $options = null, $keep = false)
    {
        if (! $this->exists($path)) {
            throw new DirectoryNotFoundException("The directory [$path] already exists.");
        }

        if (! $this->exists($destination)) {
            $this->create($destination, 0777, true);
        }

        $options = $options ?: \FilesystemIterator::SKIP_DOTS;
        $items = new \FilesystemIterator($path, $options);

        foreach ($items as $item) {
            $target = $destination.'/'.$item->getBasename();

            if ($item->isDir()) {
                $currentPath = $item->getPathname();

                if (! $this->move($currentPath, $target, $options, true)) {
                    return false;
                }
            } elseif (! File::make()->copy($item->getPathname(), $target, true)) {
                return false;
            }
        }

        if (! $keep) {
            $this->delete($path);
        }

        return true;
    }

    /**
     * Copy a directory to a new location
     *
     * @param string $path
     * @param string $destination
     * @param null|int $options
     * @return bool
     *
     * @throws \Georgeff\Filesystem\Exception\DirectoryNotFoundException
     */
    public function copy($path, $destination, $options = null)
    {
        return $this->move($path, $destination, $options, true);
    }

    /**
     * Return the contents of a directory based on a pattern
     *
     * @param string $path
     * @param string $pattern
     * @param int $flags
     * @return array
     *
     * @throws \Georgeff\Filesystem\Exception\DirectoryNotFoundException
     */
    public function glob($path, $pattern = '*', $flags = 0)
    {
        if (! $this->exists($path)) {
            throw new DirectoryNotFoundException("The directory [$path] was not found.");
        }

        return glob($path.'/'.$pattern, $flags);
    }

    /**
     * Return an array of all files in a directory
     *
     * @param string $path
     * @return array
     *
     * @throws \Georgeff\Filesystem\Exception\DirectoryNotFoundException
     */
    public function files($path)
    {
        return $this->glob($path, '*.*');
    }

    /**
     * Return all files in a directory recursively
     *
     * @param string $path
     * @return array
     *
     * @throws \Georgeff\Filesystem\Exception\DirectoryNotFoundException
     */
    public function filesRecursive($path)
    {
        if (! $this->exists($path)) {
            throw new DirectoryNotFoundException("The directory [$path] was not found.");
        }

        $files = [];

        foreach (Finder::create()->files()->in($path) as $file) {
            $files[] = $file->getPathname();
        }

        return $files;
    }

    /**
     * Get all directories within a path
     *
     * @param string $path
     * @return array
     *
     * @throws \Georgeff\Filesystem\Exception\DirectoryNotFoundException
     */
    public function directories($path)
    {
        if (! $this->exists($path)) {
            throw new DirectoryNotFoundException("The directory [$path] was not found.");
        }

        $directories = [];

        foreach (Finder::create()->directories()->in($path) as $directory)
        {
            $directories[] = $directory->getPathname();
        }

        return $directories;
    }

    /**
     * Delete a directory
     *
     * @param string $path
     * @param bool|false $keep
     * @return bool
     *
     * @throws \Georgeff\Filesystem\Exception\DirectoryNotFoundException
     */
    public function delete($path, $keep = false)
    {
        if (! $this->exists($path)) {
            throw new DirectoryNotFoundException("The directory [$path] was not found.");
        }

        $items = new \FilesystemIterator($path);

        foreach ($items as $item) {
            if ($item->isDir()) {
                $this->delete($item->getPathname());
            } else {
                File::make()->delete($item->getPathname());
            }
        }

        if (! $keep) {
            rmdir($path);
        }

        return true;
    }

    /**
     * Empty a directory's content keeping the directory
     *
     * @param string $path
     * @return bool
     *
     * @throws \Georgeff\Filesystem\Exception\DirectoryNotFoundException
     */
    public function clear($path)
    {
        return $this->delete($path, true);
    }
}