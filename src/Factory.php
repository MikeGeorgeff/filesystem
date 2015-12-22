<?php

namespace Georgeff\Filesystem;

class Factory
{
    /**
     * @var \Georgeff\Filesystem\File
     */
    protected $file;

    /**
     * @var \Georgeff\Filesystem\Directory
     */
    protected $dir;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->file = new File;
        $this->dir  = new Directory;
    }

    /**
     * Check if a file exists
     *
     * @param string $path
     * @return bool
     */
    public function fileExists($path)
    {
        return $this->file->exists($path);
    }

    /**
     * Create a new file
     *
     * @param string     $path
     * @param mixed|null $content
     * @return int
     *
     * @throws \Georgeff\Filesystem\Exception\FileExistsException
     */
    public function createFile($path, $content = null)
    {
        return $this->file->create($path, $content);
    }

    /**
     * Return the contents of a file
     *
     * @param string $path
     * @return string
     *
     * @throws \Georgeff\Filesystem\Exception\FileNotFoundException
     */
    public function fileGetContents($path)
    {
        return $this->file->get($path);
    }

    /**
     * Write contents to a file
     *
     * @param string    $path
     * @param mixed     $content
     * @param bool|true $overwrite
     * @return int
     */
    public function filePutContents($path, $content, $overwrite = true)
    {
        return $this->file->put($path, $content, $overwrite);
    }

    /**
     * Get the contents of a json file
     *
     * @param string $path
     * @return array
     *
     * @throws \Georgeff\Filesystem\Exception\FileNotFoundException
     * @throws \InvalidArgumentException
     */
    public function getJson($path)
    {
        return $this->file->getJson($path);
    }

    /**
     * Write an array to a json file
     *
     * @param string $path
     * @param array  $content
     * @param bool   $overwrite
     * @return int
     *
     * @throws \Georgeff\Filesystem\Exception\FileNotFoundException
     */
    public function putJson($path, array $content, $overwrite = true)
    {
        return $this->file->putJson($path, $content, $overwrite);
    }

    /**
     * Get the contents of a required file
     *
     * @param string $path
     * @return mixed
     *
     * @throws \Georgeff\Filesystem\Exception\FileNotFoundException
     */
    public function getRequire($path)
    {
        return $this->file->getRequire($path);
    }

    /**
     * Copy a file to a new location
     *
     * @param string  $path
     * @param string  $destination
     * @param bool    $overwrite
     * @return bool
     *
     * @throws \Georgeff\Filesystem\Exception\FileExistsException
     * @throws \Georgeff\Filesystem\Exception\FileNotFoundException
     */
    public function cpFile($path, $destination, $overwrite = true)
    {
        return $this->file->copy($path, $destination, $overwrite);
    }

    /**
     * Move a file to a new location
     *
     * @param string  $path
     * @param string  $destination
     * @param bool    $overwrite
     * @return bool
     *
     * @throws \Georgeff\Filesystem\Exception\FileExistsException
     * @throws \Georgeff\Filesystem\Exception\FileNotFoundException
     */
    public function mvFile($path, $destination, $overwrite = true)
    {
        return $this->file->move($path, $destination, $overwrite);
    }

    /**
     * Delete a file or an array of files
     *
     * @param string|array $paths
     * @return bool
     *
     * @throws \Georgeff\Filesystem\Exception\FileNotFoundException
     */
    public function unlink($paths)
    {
        return $this->file->delete($paths);
    }

    /**
     * Return the file name
     *
     * @param string $path
     * @return string
     *
     * @throws \Georgeff\Filesystem\Exception\FileNotFoundException
     */
    public function fileName($path)
    {
        return $this->file->name($path);
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
        return $this->file->extension($path);
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
        return $this->file->mimeType($path);
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
        return $this->file->size($path);
    }

    /**
     * Return the files last modified time
     *
     * @param string $path
     * @return int
     *
     * @throws \Georgeff\Filesystem\Exception\FileNotFoundException
     */
    public function lastModified($path)
    {
        return $this->file->lastModified($path);
    }

    /**
     * Determine if a directory exists
     *
     * @param string $path
     * @return bool
     */
    public function dirExists($path)
    {
        return $this->dir->exists($path);
    }

    /**
     * Create a new directory
     *
     * @param string $path
     * @param int    $mode
     * @param bool   $recursive
     * @return bool
     *
     * @throws \Georgeff\Filesystem\Exception\DirectoryExistsException
     */
    public function mkDir($path, $mode = 0777, $recursive = false)
    {
        return $this->dir->create($path, $mode, $recursive);
    }

    /**
     * Move a directory
     *
     * @param string     $path
     * @param string     $destination
     * @param null|int   $options
     * @param bool|false $keep
     * @return bool
     *
     * @throws \Georgeff\Filesystem\Exception\DirectoryNotFoundException
     */
    public function mvDir($path, $destination, $options = null, $keep = false)
    {
        return $this->dir->move($path, $destination, $options, $keep);
    }

    /**
     * Copy a directory
     *
     * @param string     $path
     * @param string     $destination
     * @param null|int   $options
     * @return bool
     */
    public function cpDir($path, $destination, $options = null)
    {
        return $this->dir->copy($path, $destination, $options);
    }

    /**
     * Return the contents of a directory based on a pattern
     *
     * @param string  $path
     * @param string  $pattern
     * @param int     $flags
     * @return array
     *
     * @throws \Georgeff\Filesystem\Exception\DirectoryNotFoundException
     */
    public function glob($path, $pattern = '*', $flags = 0)
    {
        return $this->dir->glob($path, $pattern, $flags);
    }

    /**
     * Return an array of all files in a directory
     *
     * @param string $path
     * @return array
     */
    public function files($path)
    {
        return $this->dir->files($path);
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
        return $this->dir->filesRecursive($path);
    }

    /**
     * Get all directories in a path
     *
     * @param string $path
     * @return array
     *
     * @throws \Georgeff\Filesystem\Exception\DirectoryNotFoundException
     */
    public function directories($path)
    {
        return $this->dir->directories($path);
    }

    /**
     * Delete a directory
     *
     * @param string $path
     * @param bool   $keep
     * @return bool
     *
     * @throws \Georgeff\Filesystem\Exception\DirectoryNotFoundException
     */
    public function deleteDir($path, $keep = false)
    {
        return $this->dir->delete($path, $keep);
    }

    /**
     * Delete the contents of a directory keeping the directory
     *
     * @param string $path
     * @return bool
     */
    public function clearDir($path)
    {
        return $this->dir->clear($path);
    }
}