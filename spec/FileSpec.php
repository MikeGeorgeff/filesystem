<?php

use Georgeff\Filesystem\File;
use Georgeff\Filesystem\Exception\FileNotFoundException;
use Georgeff\Filesystem\Exception\FileExistsException;

describe(File::class, function () {

    beforeEach(function () {
        file_put_contents(__DIR__.'/file.txt', 'Hello World');
    });

    afterEach(function () {
        if (file_exists(__DIR__.'/file.txt')) {
            unlink(__DIR__ . '/file.txt');
        }
    });

    describe("::make()", function () {

        it("creates a new static instance", function () {
            expect(File::make())->toBeAnInstanceOf('Georgeff\Filesystem\File');
        });

    });

    describe("->exists()", function () {

        it("returns true if the file exists", function () {
            $file = new File;
            expect($file->exists(__DIR__.'/file.txt'))->toBe(true);
        });

        it("returns false if the file does not exist", function () {
            $file = new File;
            expect($file->exists(__DIR__.'/foo.txt'))->toBe(false);
        });

    });

    describe("->create()", function () {

        it("creates a new file", function () {
            $file = new File;
            $file->create(__DIR__.'/foo.txt', 'Foo');
            $exists = $file->exists(__DIR__.'/foo.txt');
            $content = file_get_contents(__DIR__.'/foo.txt');
            @unlink(__DIR__.'/foo.txt');

            expect($exists)->toBe(true);
            expect($content)->toBe('Foo');
        });

        it("throws a FileExistsException if the file already exists", function () {
            $file = new File;
            expect(function () use ($file) { $file->create(__DIR__.'/file.txt', 'foo'); })
                ->toThrow(new FileExistsException);
        });

    });

    describe("->get()", function () {

        it("returns the contents of a file", function () {
            $file = new File;
            expect($file->get(__DIR__.'/file.txt'))->toBe('Hello World');
        });

        it("throws a FileNotFoundException if the file is not found", function () {
            $file = new File;
            expect(function () use ($file) { $file->get('foo.txt'); })
                ->toThrow(new FileNotFoundException);
        });

    });

    describe("->put()", function () {

        it("writes content to a file, overwriting the original content", function () {
            $file = new File;
            $file->put(__DIR__.'/file.txt', 'New Content');
            expect(file_get_contents(__DIR__.'/file.txt'))->toBe('New Content');
        });

        it("appends content to a file", function () {
            $file = new File;
            $file->put(__DIR__.'/file.txt', 'New Content', false);
            expect(file_get_contents(__DIR__.'/file.txt'))->toBe('Hello WorldNew Content');
        });

    });

    describe("->getRequire()", function () {

        it("returns the content of a required file", function () {
            $file = new File;
            file_put_contents(__DIR__.'/index.php', '<?php return "Hello World"; ?>');
            $content = $file->getRequire(__DIR__.'/index.php');
            @unlink(__DIR__.'/index.php');

            expect($content)->toBe('Hello World');
        });

        it("throws a FileNotFoundException if the required file is not found", function () {
            $file = new File;
            expect(function () use ($file) { $file->getRequire('index.php'); })
                ->toThrow(new FileNotFoundException);
        });

    });

    describe("->copy()", function () {

        beforeEach(function () {
            mkdir(__DIR__.'/folder');
        });

        afterEach(function () {
            $files = glob(__DIR__.'/folder/*.*');

            foreach ($files as $file) {
                @unlink($file);
            }

            rmdir(__DIR__.'/folder');
        });

        it("copies a file to a new location", function () {
            $file = new File;
            $file->copy(__DIR__.'/file.txt', __DIR__.'/folder/file.txt');

            expect($file->exists(__DIR__.'/folder/file.txt'))->toBe(true);
            expect($file->exists(__DIR__.'/file.txt'))->toBe(true);
        });

        it("copies a file to a new location, overwriting an existing file with the same name", function () {
            file_put_contents(__DIR__.'/folder/file.txt', 'Howdy');
            $file = new File;
            $file->copy(__DIR__.'/file.txt', __DIR__.'/folder/file.txt');

            expect(file_get_contents(__DIR__.'/folder/file.txt'))->toBe('Hello World');
        });

        it("throws a FileExistsException if the overwrite argument is set to false", function () {
            file_put_contents(__DIR__.'/folder/file.txt', 'Howdy');
            $file = new File;

            expect(function () use ($file) { $file->copy(__DIR__.'/file.txt', __DIR__.'/folder/file.txt', false); })
                ->toThrow(new FileExistsException);
        });

        it("throws a FileNotFoundException if the source file does not exist", function () {
            $file = new File;
            expect(function () use ($file) { $file->copy('file.txt', 'file.txt'); })->toThrow(new FileNotFoundException);
        });

    });

    describe("->move()", function () {

        beforeEach(function () {
            mkdir(__DIR__.'/folder');
        });

        afterEach(function () {
            $files = glob(__DIR__.'/folder/*.*');

            foreach ($files as $file) {
                @unlink($file);
            }

            rmdir(__DIR__.'/folder');
        });

        it("moves a file to a new location", function () {
            $file = new File;
            $file->move(__DIR__.'/file.txt', __DIR__.'/folder/file.txt');

            expect($file->exists(__DIR__.'/folder/file.txt'))->toBe(true);
            expect($file->exists(__DIR__.'/file.txt'))->toBe(false);
        });

        it("moves a file to a new location, overwriting an existing file with the same name", function () {
            file_put_contents(__DIR__.'/folder/file.txt', 'Howdy');
            $file = new File;
            $file->move(__DIR__.'/file.txt', __DIR__.'/folder/file.txt');

            expect(file_get_contents(__DIR__.'/folder/file.txt'))->toBe('Hello World');
        });

        it("throws a FileExistsException if the overwrite argument is set to false", function () {
            file_put_contents(__DIR__.'/folder/file.txt', 'Howdy');
            $file = new File;

            expect(function () use ($file) { $file->move(__DIR__.'/file.txt', __DIR__.'/folder/file.txt', false); })
                ->toThrow(new FileExistsException);
        });

        it("throws a FileNotFoundException if the source file does not exist", function () {
            $file = new File;
            expect(function () use ($file) { $file->move('file.txt', 'file.txt'); })->toThrow(new FileNotFoundException);
        });

    });

    describe("->delete()", function () {

        it("deletes a single file", function () {
            $file = new File;

            expect($file->delete(__DIR__.'/file.txt'))->toBe(true);
            expect($file->exists(__DIR__.'/file.txt'))->toBe(false);
        });

        it("deletes an array of files", function () {
            file_put_contents(__DIR__.'/file2.txt', '');
            $file = new File;

            expect($file->delete([__DIR__.'/file.txt', __DIR__.'/file2.txt']))->toBe(true);
            expect($file->exists(__DIR__.'/file.txt'))->toBe(false);
            expect($file->exists(__DIR__.'/file2.txt'))->toBe(false);
        });

        it("throws a FileNotFoundException if the file does not exist", function () {
            $file = new File;

            expect(function () use ($file) { $file->delete('file.txt'); })->toThrow(new FileNotFoundException);
        });

    });

    describe("->name()", function () {

        it("returns the file name", function () {
            $file = new File;

            expect($file->name(__DIR__.'/file.txt'))->toBe('file');
        });

        it("throws a FileNotFoundException if the file is not found", function () {
            $file = new File;

            expect(function () use ($file) { $file->name('file.txt'); })->toThrow(new FileNotFoundException);
        });

    });

    describe("->extension()", function () {

        it("returns the file extension", function () {
            $file = new File;

            expect($file->extension(__DIR__.'/file.txt'))->toBe('txt');
        });

        it("throws a FileNotFoundException if the file is not found", function () {
            $file = new File;

            expect(function () use ($file) { $file->extension('file.txt'); })->toThrow(new FileNotFoundException);
        });

    });

    describe("->mimeType()", function () {

        it("returns the files mimeType", function () {
            $file = new File;

            expect($file->mimeType(__DIR__.'/file.txt'))->toBe('text/plain');
        });

        it("throws a FileNotFoundException if the file is not found", function () {
            $file = new File;

            expect(function () use ($file) { $file->mimeType('file.txt'); })->toThrow(new FileNotFoundException);
        });

    });

    describe("->size()", function () {

        it("returns the files size", function () {
            $file = new File;

            expect($file->size(__DIR__.'/file.txt'))->toBe(11);
        });

        it("throws a FileNotFoundException if the file is not found", function () {
            $file = new File;

            expect(function () use ($file) { $file->size('file.txt'); })->toThrow(new FileNotFoundException);
        });

    });

    describe("->lastModified()", function () {

        it("returns the files lastModified()", function () {
            $file = new File;
            $timestamp = $file->lastModified(__DIR__.'/file.txt');
            $human = date('Y-m-d', $timestamp);

            expect($human)->toBe(date('Y-m-d'));
        });

        it("throws a FileNotFoundException if the file is not found", function () {
            $file = new File;

            expect(function () use ($file) { $file->lastModified('file.txt'); })->toThrow(new FileNotFoundException);
        });

    });

    describe("->getJson()", function () {

        it("returns the contents of a json file as an array", function () {
            $contents = '{ "require": { "php": "<=5.5.9" }, "require-dev": { "some/package": "1.0" } }';
            file_put_contents(__DIR__.'/file.json', $contents);
            $file = new File;
            $result = $file->getJson(__DIR__.'/file.json');
            unlink(__DIR__.'/file.json');
            expect($result)->toBe([
                'require' => [
                    'php' => '<=5.5.9'
                ],
                'require-dev' => [
                    'some/package' => '1.0'
                ]
            ]);
        });

        it("throws an InvalidArgumentException if the file is not a json file", function () {
            file_put_contents(__DIR__.'/file.txt', '');
            $closure = function () {
                $file = new File;
                $file->getJson(__DIR__.'/file.txt');
            };

            expect($closure)->toThrow(new InvalidArgumentException);
            unlink(__DIR__.'/file.txt');
        });

        it("throws a FileNotFoundException if the file does not exist", function () {
            $closure = function () {
                $file = new File;
                $file->getJson(__DIR__.'/file.json');
            };

            expect($closure)->toThrow(new FileNotFoundException);
        });

    });

    describe("->putJson()", function () {

        it("writes an array to a json file", function () {
            $file = new File;
            $file->putJson(__DIR__.'/file.json', ['foo' => 'bar']);
            $result = $file->getJson(__DIR__.'/file.json');
            unlink(__DIR__.'/file.json');
            expect($result)->toBe(['foo' => 'bar']);
        });

        it("appends an array to a json file", function () {
            $contents = '{ "require": { "php": "<=5.5.9" }, "require-dev": { "some/package": "1.0" } }';
            file_put_contents(__DIR__.'/file.json', $contents);
            $file = new File;
            $file->putJson(__DIR__.'/file.json', ['foo' => 'bar'], false);
            $result = $file->getJson(__DIR__.'/file.json');
            unlink(__DIR__.'/file.json');
            expect($result)->toBe([
                'require' => [
                    'php' => '<=5.5.9'
                ],
                'require-dev' => [
                    'some/package' => '1.0'
                ],
                'foo' => 'bar'
            ]);
        });

        it("throws a FileNotFoundException if overwrite is false and the file is not found", function () {
            $closure = function () {
                $file = new File;
                $file->putJson(__DIR__.'/file.json', ['foo' => 'bar'], false);
            };

            expect($closure)->toThrow(new FileNotFoundException);
        });

    });

});