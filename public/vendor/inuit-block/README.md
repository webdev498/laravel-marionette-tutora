# Block

inuitcss’ Block object simply stacks an image on top of some text content.

This incredibly frequently occurring design pattern is now wrapped up in a
simple, reusable, configurable abstraction.

## Dependencies

inuitcss’ Block object depends on two other inuitcss modules:

* [settings.defaults](https://github.com/inuitcss/settings.defaults)
* [tools.functions](https://github.com/inuitcss/tools.functions)

If you install the Block object using Bower, you will get these dependencies at
the same time. If not using Bower, please be sure to install and `@import` these
dependencies in the relevant way.

## Installation

The recommended installation method is Bower, but you can install the Block
module via a Git Submodule, or copy and paste.

### Install using Bower:

    $ bower install --save inuit-block

Once installed, `@import` into your project in its Objects layer:

    @import "bower_components/inuit-block/objects.block";

### Install as a Git Submodule

    $ git submodule add git@github.com:inuitcss/objects.block.git

Once installed, `@import` into your project in its Objects layer:

    @import "objects.block/objects.block";

### Install via file download

The least recommended option for installation is to simply download
`_objects.block.scss` into your project and `@import` it into your project in
its Objects layer.

## Usage

Basic usage of the Block object uses the required classes:

    <div class="block">
        <img src="/path/to/image.png" alt="" class="block__img" />
        <div class="block__body">
            <p>Text-like content goes here.</p>
        </div>
    </div>

The only valid children of the `.block` node are `.block__img` and
`.block__body`.

## Options

Other, optional classes can supplement the required base classes:

* `.block--flush`: remove the space between the stacked image- and text-content.
* `.block--[tiny|small|large|huge]`: alter the spacing between the stacked
  image- and text-content.
* `.block--[center|right]`: align both the image- and text-content.

For example:

    <div class="block  block--small  block--center">
        <img src="/path/to/image.png" alt="" class="block__img" />
        <div class="block__body">
            <p>Text-like content goes here.</p>
        </div>
    </div>
