<?php

use Georgeff\Filesystem\Factory;

describe(Factory::class, function () {

    describe("::file()", function () {

        it("returns an instance of Georgeff\\Filesystem\\File", function () {
            expect(Factory::file())->toBeAnInstanceOf('Georgeff\Filesystem\File');
        });

    });

    describe("::dir()", function () {

        it("returns an instance of Georgeff\\Filesystem\\Directory", function () {
            expect(Factory::dir())->toBeAnInstanceOf('Georgeff\Filesystem\Directory');
        });

    });

});