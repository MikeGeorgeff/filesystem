<?php

use Georgeff\Filesystem\Directory;
use Georgeff\Filesystem\Exception\DirectoryNotFoundException;
use Georgeff\Filesystem\Exception\DirectoryExistsException;

describe(Directory::class, function () {

    describe("->exists()", function () {

        beforeEach(function () {
            mkdir(__DIR__.'/folder');
        });

        afterEach(function () {
            rmdir(__DIR__.'/folder');
        });

        it("returns true if the directory exists", function () {
            $dir = new Directory;

            expect($dir->exists(__DIR__.'/folder'))->toBe(true);
        });

        it("returns false if the directory does not exist", function () {
            $dir = new Directory;

            expect($dir->exists('/folder'))->toBe(false);
        });

    });

    describe("->create()", function () {

        it("creates a new directory", function () {
            $dir = new Directory;
            $dir->create(__DIR__.'/folder');
            $exists = is_dir(__DIR__.'/folder');
            rmdir(__DIR__.'/folder');

            expect($exists)->toBe(true);
        });

        it("throws a DirectoryExistsException if the directory already exists", function () {
            mkdir(__DIR__.'/folder');
            $dir = new Directory;

            expect(function () use ($dir) { $dir->create(__DIR__.'/folder'); })->toThrow(new DirectoryExistsException);
            rmdir(__DIR__.'/folder');
        });

    });

    describe("->glob()", function () {

        beforeEach(function () {
            mkdir(__DIR__.'/folder');
            mkdir(__DIR__.'/folder/vendor');
            file_put_contents(__DIR__.'/folder/index.php', '');
            file_put_contents(__DIR__.'/folder/functions.php', '');
        });

        afterEach(function () {
            unlink(__DIR__.'/folder/index.php');
            unlink(__DIR__.'/folder/functions.php');
            rmdir(__DIR__.'/folder/vendor');
            rmdir(__DIR__.'/folder');
        });

        it("returns an array of all contents in a directory", function () {
            $dir = new Directory;
            $result = $dir->glob(__DIR__.'/folder');

            expect($result)->toBe([
                __DIR__.'/folder/functions.php',
                __DIR__.'/folder/index.php',
                __DIR__.'/folder/vendor'
            ]);
        });

        it("throws a DirectoryNotFoundException if the directory does not exist.", function () {
            $dir = new Directory;

            expect(function () use ($dir) { $dir->glob('/dir'); })->toThrow(new DirectoryNotFoundException);
        });

    });

    describe("->files()", function () {

        beforeEach(function () {
            mkdir(__DIR__.'/folder');
            mkdir(__DIR__.'/folder/vendor');
            file_put_contents(__DIR__.'/folder/index.php', '');
            file_put_contents(__DIR__.'/folder/functions.php', '');
        });

        afterEach(function () {
            unlink(__DIR__.'/folder/index.php');
            unlink(__DIR__.'/folder/functions.php');
            rmdir(__DIR__.'/folder/vendor');
            rmdir(__DIR__.'/folder');
        });

        it("returns an array of all files in a directory", function () {
            $dir = new Directory;
            $result = $dir->files(__DIR__.'/folder');

            expect($result)->toBe([
                __DIR__.'/folder/functions.php',
                __DIR__.'/folder/index.php'
            ]);
        });

    });

    describe("->filesRecursive()", function () {

        beforeEach(function () {
            mkdir(__DIR__.'/folder');
            mkdir(__DIR__.'/folder/vendor');
            file_put_contents(__DIR__.'/folder/index.php', '');
            file_put_contents(__DIR__.'/folder/functions.php', '');
            file_put_contents(__DIR__.'/folder/vendor/file.php', '');
        });

        afterEach(function () {
            unlink(__DIR__.'/folder/index.php');
            unlink(__DIR__.'/folder/functions.php');
            unlink(__DIR__.'/folder/vendor/file.php');
            rmdir(__DIR__.'/folder/vendor');
            rmdir(__DIR__.'/folder');
        });

        it("recursively returns an array of all files within a directory", function () {
            $dir = new Directory;
            $result = $dir->filesRecursive(__DIR__.'/folder');

            expect($result)->toContain(__DIR__.'/folder/index.php');
            expect($result)->toContain(__DIR__.'/folder/functions.php');
            expect($result)->toContain(__DIR__.'/folder/vendor/file.php');
        });

        it("throws a DirectoryNotFoundException if the directory does not exist", function () {
            $dir = new Directory;

            expect(function () use ($dir) { $dir->filesRecursive('/folder'); })->toThrow(new DirectoryNotFoundException);
        });

    });

    describe("->directories()", function () {

        beforeEach(function () {
            mkdir(__DIR__.'/app');
            mkdir(__DIR__.'/app/controllers');
            mkdir(__DIR__.'/app/models');
            mkdir(__DIR__.'/app/views');
        });

        afterEach(function () {
            rmdir(__DIR__.'/app/controllers');
            rmdir(__DIR__.'/app/models');
            rmdir(__DIR__.'/app/views');
            rmdir(__DIR__.'/app');
        });

        it("returns an array of all directories within a path", function () {
            $dir = new Directory;
            $result = $dir->directories(__DIR__.'/app');

            expect($result)->toContain(__DIR__.'/app/controllers');
            expect($result)->toContain(__DIR__.'/app/models');
            expect($result)->toContain(__DIR__.'/app/views');
        });

        it("throws a DirectoryNotFoundException if the directory does not exist.", function () {
            $dir = new Directory;

            expect(function () use ($dir) { $dir->directories('/folder'); })->toThrow(new DirectoryNotFoundException);
        });

    });

    describe("->delete()", function () {

        it("deletes a directory an all it's contents", function () {
            mkdir(__DIR__.'/app');
            mkdir(__DIR__.'/app/controllers');
            mkdir(__DIR__.'/app/models');
            file_put_contents(__DIR__.'/app/router.php', '');
            file_put_contents(__DIR__.'/app/controllers/Controller.php', '');
            file_put_contents(__DIR__.'/app/controllers/AuthController.php', '');
            file_put_contents(__DIR__.'/app/models/User.php', '');
            $dir = new Directory;
            $result = $dir->delete(__DIR__.'/app');

            expect($result)->toBe(true);
            expect(file_exists(__DIR__.'/app/controllers/Controller.php'))->toBe(false);
            expect(file_exists(__DIR__.'/app/controllers/AuthController.php'))->toBe(false);
            expect(file_exists(__DIR__.'/app/models/User.php'))->toBe(false);
            expect(file_exists(__DIR__.'/app/router.php'))->toBe(false);
            expect(file_exists(__DIR__.'/app/models'))->toBe(false);
            expect(file_exists(__DIR__.'/app/controllers'))->toBe(false);
            expect(file_exists(__DIR__.'/app'))->toBe(false);
        });

        it("throws a DirectoryNotFoundException if the directory does not exist", function () {
            $dir = new Directory;

            expect(function () use ($dir) { $dir->delete('/folder'); })->toThrow(new DirectoryNotFoundException);
        });

    });

    describe("->clear()", function () {

        it("empties the contents of a directory, saving the directory", function () {
            mkdir(__DIR__.'/app');
            mkdir(__DIR__.'/app/controllers');
            mkdir(__DIR__.'/app/models');
            file_put_contents(__DIR__.'/app/router.php', '');
            file_put_contents(__DIR__.'/app/controllers/Controller.php', '');
            file_put_contents(__DIR__.'/app/controllers/AuthController.php', '');
            file_put_contents(__DIR__.'/app/models/User.php', '');
            $dir = new Directory;
            $result = $dir->clear(__DIR__.'/app');

            expect($result)->toBe(true);
            expect(file_exists(__DIR__.'/app/controllers/Controller.php'))->toBe(false);
            expect(file_exists(__DIR__.'/app/controllers/AuthController.php'))->toBe(false);
            expect(file_exists(__DIR__.'/app/models/User.php'))->toBe(false);
            expect(file_exists(__DIR__.'/app/router.php'))->toBe(false);
            expect(file_exists(__DIR__.'/app/models'))->toBe(false);
            expect(file_exists(__DIR__.'/app/controllers'))->toBe(false);
            expect(file_exists(__DIR__.'/app'))->toBe(true);
            rmdir(__DIR__.'/app');
        });

        it("throws a DirectoryNotFoundException if the directory does not exist", function () {
            $dir = new Directory;

            expect(function () use ($dir) { $dir->clear('/folder'); })->toThrow(new DirectoryNotFoundException);
        });

    });

    describe("->move()", function () {

        it("moves a directory to a different location", function () {
            mkdir(__DIR__.'/folder');
            mkdir(__DIR__.'/app');
            mkdir(__DIR__.'/app/controllers');
            file_put_contents(__DIR__.'/app/router.php', '');
            file_put_contents(__DIR__.'/app/controllers/Controller.php', '');
            $dir = new Directory;
            $dir->move(__DIR__.'/app', __DIR__.'/folder/app');

            expect(file_exists(__DIR__.'/app'))->toBe(false);
            expect(file_exists(__DIR__.'/folder/app'))->toBe(true);
            expect(file_exists(__DIR__.'/folder/app/controllers'))->toBe(true);
            expect(file_exists(__DIR__.'/folder/app/controllers/Controller.php'))->toBe(true);
            expect(file_exists(__DIR__.'/folder/app/router.php'))->toBe(true);

            $dir->delete(__DIR__.'/folder');
        });

        it("throws a DirectoryNotFoundException if the directory does not exist", function () {
            $dir = new Directory;

            expect(function () use ($dir) { $dir->move('/folder', ''); })->toThrow(new DirectoryNotFoundException);
        });

    });

    describe("->copy()", function () {

        it("copies a directory to a new location", function () {
            mkdir(__DIR__.'/folder');
            mkdir(__DIR__.'/app');
            mkdir(__DIR__.'/app/controllers');
            file_put_contents(__DIR__.'/app/router.php', '');
            file_put_contents(__DIR__.'/app/controllers/Controller.php', '');
            $dir = new Directory;
            $dir->copy(__DIR__.'/app', __DIR__.'/folder/app');

            expect(file_exists(__DIR__.'/app'))->toBe(true);
            expect(file_exists(__DIR__.'/folder/app'))->toBe(true);
            expect(file_exists(__DIR__.'/folder/app/controllers'))->toBe(true);
            expect(file_exists(__DIR__.'/folder/app/controllers/Controller.php'))->toBe(true);
            expect(file_exists(__DIR__.'/folder/app/router.php'))->toBe(true);

            $dir->delete(__DIR__.'/app');
            $dir->delete(__DIR__.'/folder');
        });

    });

});