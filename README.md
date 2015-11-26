# Filesystem

A simple php file system wrapper.

## Installation

Add `"georgeff/filesystem": "~1.0"` to your composer.json file and run a composer install.

## Classes

### File

namespace Georgeff\Filesystem

#### Methods

##### static make()
Make a new instance of the File class

return Georgeff\Filesystem\File

##### exists($path)
Check if a file exists

string $path

return bool

##### create($path, $content = null)
Create a new file

string     $path

mixed|null $content

return     int

throws     Georgeff\Filesystem\Exception\FileExistsException if the file exists at the given path

##### get($path)
Get the contents of a file

string $path

return string

throws Georgeff\Filesystem\Exception\FileNotFoundException if the file does not exists at the given path

##### put($path, $content, $overwrite = true)
Write content to a file.  If the file doesn't exist, it will be created.

string    $path

mixed     $content

bool|true $overwrite if true overwrite existing content, false append the content

return    int

##### getRequire($path)
Get the content of a required file

string $path

return mixed

throws Georgeff\Filesystem\Exception\FileNotFoundException

##### copy($path, $destination, $overwrite = true)
Copy a file to a new location

string    $path

string    $destination

bool|true $overwrite  true - if the file exists at the destination it will be replaced

return    bool

throws    Georgeff\Filesystem\Exception\FileExistsException  if the file exists at the destination and $overwrite = false

throws    Georgeff\Filesystem\Exception\FileNotFoundException

##### move($path, $destination, $overwrite = true)
Move a file to a new location

string    $path

string    $destination

bool|true $overwrite  true - if the file exists at the destination it will be replaced

return    bool

throws    Georgeff\Filesystem\Exception\FileExistsException  if the file exists at the destination and $overwrite = false

throws    Georgeff\Filesystem\Exception\FileNotFoundException

##### delete($paths)
Delete a file or an array of files

string|array $path

return       bool

throws       Georgeff\Filesystem\Exception\FileNotFoundException

##### name($path)
Extract the file name

string $path

return string

throws Georgeff\Filesystem\Exception\FileNotFoundException

##### extension($path)
Extract the file extension

string $path

return string

throws Georgeff\Filesystem\Exception\FileNotFoundException

##### mimeType($path)
Return the files mime type

string $path

return string

throws Georgeff\Filesystem\Exception\FileNotFoundException

##### size($path)
Return the file's size

string $path

return int

throws Georgeff\Filesystem\Exception\FileNotFoundException

##### lastModified($path)
Return the last modified timestamp

string $path

return int unix timestamp  

throws Georgeff\Filesystem\Exception\FileNotFoundException

### Directory
namespace Georgeff\Filesystem

#### Methods

##### static make()
Make a new directory instance

return Georgeff\Filesystem\Directory

##### exists($path)
Check if a directory exists

string $path

return bool

##### create($path, $mode = 0777, $recursive = false)
Create a new directory

string      $path

int         $mode

bool|false  $recursive

return      bool

throws      Georgeff\Filesystem\Exception\DirectoryExistsException

##### move($path, $destination, $options = null, $keep = false)
Move a directory to a new location

string     $path

string     $destination

int        $options

bool|false $keep if true the original file will be preserved at the original location

return     bool

throws     Georgeff\Filesystem\Exception\DirectoryNotFoundException

throws     Georgeff\Filesystem\Exception\DirectoryExistsException

##### copy($path, $destination, $options = null)

string    $path

string    $destination

int|null  $options

return    bool

throws    Georgeff\Filesystem\Exception\DirectoryNotFoundException

throws    Georgeff\Filesystem\Exception\DirectoryExistsException

##### glob($path, $pattern = '\*', $flags = 0)
Return the contents of a directory based on a given pattern

string $path

string $pattern

int    $flags

return array

throws Georgeff\Filesystem\Exception\DirectoryNotFoundException

##### files($path)
Return all the files within a directory

string $path

return array

throws Georgeff\Filesystem\Exception\DirectoryNotFoundException

##### filesRecursive($path)
Return all files in a directory recursively

string $path

return array

throws Georgeff\Filesystem\Exception\DirectoryNotFoundException

##### directories($path)
Get all directories within a given path

string $path

return array

throws Georgeff\Filesystem\Exception\DirectoryNotFoundException

##### delete($path, $keep = false)
Delete a directory

string      $path

bool|false  $keep  if true the directory will be emptied, but preserved

return bool

throws Georgeff\Filesystem\Exception\DirectoryNotFoundException

##### clear($path)
Delete all the contents in a directory, but keep the directory

string $path

return bool

throws Georgeff\Filesystem\Exception\DirectoryNotFoundException

### Factory
namespace Georgeff\Filesystem

#### Methods

##### static file()
return Georgeff\Filesystem\File

##### static dir()
return Georgeff\Filesystem\Directory 
