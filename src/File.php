<?php

namespace Georgeff\Filesystem;

use Georgeff\Filesystem\Exception\FileNotFoundException;
use Georgeff\Filesystem\Exception\FileExistsException;

class File
{
    /**
     * Make a new File instance
     *
     * @return \Georgeff\Filesystem\File
     */
    public static function make()
    {
        return new static();
    }

    /**
     * Check if a file exists
     *
     * @param string $path
     * @return bool
     */
    public function exists($path)
    {
        return is_file($path);
    }

    /**
     * Create a new file
     *
     * @param string $path
     * @param mixed|null $content
     * @return int
     *
     * @throws \Georgeff\Filesystem\Exception\FileExistsException
     */
    public function create($path, $content = null)
    {
        if ($this->exists($path)) {
            throw new FileExistsException("The file [$path] already exists.");
        }

        return file_put_contents($path, $content);
    }

    /**
     * Get the contents of a file
     *
     * @param string $path
     * @return string
     *
     * @throws \Georgeff\Filesystem\Exception\FileNotFoundException
     */
    public function get($path)
    {
        if (! $this->exists($path)) {
            throw new FileNotFoundException("The file [$path] does not exist.");
        }

        return file_get_contents($path);
    }

    /**
     * Write contents to a file
     * If the file does not exist it will be created
     *
     * @param string $path
     * @param mixed $content
     * @param bool|true $overwrite
     * @return int
     */
    public function put($path, $content, $overwrite = true)
    {
        return file_put_contents($path, $content, $overwrite ? 0 : FILE_APPEND);
    }

    /**
     * Get the content of a required file
     *
     * @param string $path
     * @return mixed
     *
     * @throws \Georgeff\Filesystem\Exception\FileNotFoundException
     */
    public function getRequire($path)
    {
        if (! $this->exists($path)) {
            throw new FileNotFoundException("The file [$path] does not exist.");
        }

        return require $path;
    }

    /**
     * Copy a file to a new location
     *
     * @param string $path
     * @param string $destination
     * @param bool|true $overwrite
     * @return bool
     *
     * @throws \Georgeff\Filesystem\Exception\FileExistsException
     * @throws \Georgeff\Filesystem\Exception\FileNotFoundException
     */
    public function copy($path, $destination, $overwrite = true)
    {
        if ($this->exists($path)) {
            if ($this->exists($destination) && $overwrite) {
                return copy($path, $destination);
            } elseif ($this->exists($destination) && ! $overwrite) {
                throw new FileExistsException("The destination file [$destination] already exists.");
            } else {
                return copy($path, $destination);
            }
        }

        throw new FileNotFoundException("The source file [$path] does not exist.");
    }

    /**
     * Move a file to a new location
     *
     * @param string $path
     * @param string $destination
     * @param bool|true $overwrite
     * @return bool
     *
     * @throws \Georgeff\Filesystem\Exception\FileExistsException
     * @throws \Georgeff\Filesystem\Exception\FileNotFoundException
     */
    public function move($path, $destination, $overwrite = true)
    {
        if ($this->exists($path)) {
            if ($this->exists($destination) && $overwrite) {
                return rename($path, $destination);
            } elseif ($this->exists($destination) && ! $overwrite) {
                throw new FileExistsException("The destination file [$destination] already exists.");
            } else {
                return rename($path, $destination);
            }
        }

        throw new FileNotFoundException("The source file [$path] does not exist.");
    }

    /**
     * Delete a file or an array of files
     *
     * @param string|array $paths
     * @return bool
     *
     * @throws \Georgeff\Filesystem\Exception\FileNotFoundException
     */
    public function delete($paths)
    {
        $paths = is_string($paths) ? (array) $paths : $paths;

        foreach ($paths as $path) {
            if (! $this->exists($path)) {
                throw new FileNotFoundException("The file [$path] was not found.");
            }

            unlink($path);
        }

        return true;
    }

    /**
     * Return the file name
     *
     * @param string $path
     * @return string
     *
     * @throws \Georgeff\Filesystem\Exception\FileNotFoundException
     */
    public function name($path)
    {
        if (! $this->exists($path)) {
            throw new FileNotFoundException("The file [$path] does not exist.");
        }

        return pathinfo($path, PATHINFO_FILENAME);
    }

    /**
     * Return the file extension
     *
     * @param string $path
     * @return string
     *
     * @throws \Georgeff\Filesystem\Exception\FileNotFoundException
     */
    public function extension($path)
    {
        if (! $this->exists($path)) {
            throw new FileNotFoundException("The file [$path] does not exist.");
        }

        return pathinfo($path, PATHINFO_EXTENSION);
    }

    /**
     * Return the file mime type
     *
     * @param string $path
     * @return string
     *
     * @throws \Georgeff\Filesystem\Exception\FileNotFoundException
     */
    public function mimeType($path)
    {
        if (! $this->exists($path)) {
            throw new FileNotFoundException("The file [$path] does not exist.");
        }

        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        return finfo_file($finfo, $path);
    }

    /**
     * Return the file size
     *
     * @param string $path
     * @return int
     *
     * @throws \Georgeff\Filesystem\Exception\FileNotFoundException
     */
    public function size($path)
    {
        if (! $this->exists($path)) {
            throw new FileNotFoundException("The file [$path] does not exist.");
        }

        return filesize($path);
    }

    /**
     * Return the file's last modified time
     *
     * @param string $path
     * @return int
     *
     * @throws \Georgeff\Filesystem\Exception\FileNotFoundException
     */
    public function lastModified($path)
    {
        if (! $this->exists($path)) {
            throw new FileNotFoundException("The file [$path] does not exist.");
        }

        return filemtime($path);
    }
}