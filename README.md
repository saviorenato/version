
# saviorenato/version

**saviorenato/version** is a library that helps with managing the version number of Git-hosted PHP projects.

## Installation

You can add this library as a local, per-project dependency to your project using [Composer](https://getcomposer.org/):

```
composer require saviorenato/version
```

If you only need this library during development, for instance to run your project's test suite, then you should add it as a development-time dependency:

```
composer require --dev saviorenato/version
```
## Usage

The constructor of the `SavioPereira\Version` class expects two parameters:

* `$release` is the version number of the latest release (`X.Y.Z`, for instance) or the name of the release series (`X.Y`) when no release has been made from that branch / for that release series yet.
* `$path` is the path to the directory (or a subdirectory thereof) where the sourcecode of the project can be found. Simply passing `__DIR__` here usually suffices.

Apart from the constructor, the `SavioPereira\Version` class has a single public method: `versionGit()`.

Here is a contrived example that shows the basic usage:

```php
<?php 
use SavioPereira\Version;

$version = new Version('1.0.0', __DIR__);

var_dump($version->versionGit());
```
```
string(18) "1.0.0-51-6c0648fcb"
```

When a new release is prepared, the string that is passed to the constructor as the first argument needs to be updated.

### How SavioPereira\Version::versionGit() works

* If `$path` is not (part of) a Git repository and `$release` is in `X.Y.Z` format then `$release` is returned as-is.
* If `$path` is not (part of) a Git repository and `$release` is in `X.Y` format then `$release` is returned suffixed with `-dev`.
* If `$path` is (part of) a Git repository and `$release` is in `X.Y.Z` format then the output of `git describe --tags` is returned as-is.
* If `$path` is (part of) a Git repository and `$release` is in `X.Y` format then a string is returned that begins with `X.Y` and ends with information from `git describe --tags`.