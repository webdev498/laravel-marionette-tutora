# Box

The Box object simply boxes off content.

## Dependencies

inuitcss’ Box object depends on three other inuitcss modules:

* [settings.defaults](https://github.com/inuitcss/settings.defaults)
* [tools.functions](https://github.com/inuitcss/tools.functions)
* [trumps.clearfix](https://github.com/inuitcss/trumps.clearfix)

If you install the Box object using Bower, you will get these dependencies at
the same time. If not using Bower, please be sure to install and `@import` these
dependencies in the relevant way.

## Installation

The recommended installation method is Bower, but you can install the Box
module via a Git Submodule, or copy and paste.

### Install using Bower:

    $ bower install --save inuit-box

Once installed, `@import` into your project in its Objects layer:

    @import "bower_components/inuit-box/objects.box";

### Install as a Git Submodule

    $ git submodule add git@github.com:inuitcss/objects.box.git

Once installed, `@import` into your project in its Objects layer:

    @import "objects.box/objects.box";

### Install via file download

The least recommended option for installation is to simply download
`_objects.box.scss` into your project and `@import` it into your project in
its Objects layer.

## Usage

Basic usage of the Box object uses the required classes:

    <div class="box">
        Foo Bar Baz
    </div>

## Options

Other, optional classes can supplement the required base classes:

* `.box--flush`: remove all padding from boxes.
* `.box--[tiny|small|large|huge]`: alter the padding on boxes.

For example:

    <div class="box  box--large">
        Foo Bar Baz
    </div>
