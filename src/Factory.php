<?php

namespace Georgeff\Filesystem;

class Factory
{
    /**
     * Return the File instance
     *
     * @return \Georgeff\Filesystem\File
     */
    public static function file()
    {
        return new File;
    }

    /**
     * Return the Directory instance
     *
     * @return \Georgeff\Filesystem\Directory
     */
    public static function dir()
    {
        return new Directory;
    }
}