<div align="center">

# Deep Closure Comparator

[![Coverage](https://img.shields.io/coverallsCoverage/github/eliashaeussler/deep-closure-comparator?logo=coveralls)](https://coveralls.io/github/eliashaeussler/deep-closure-comparator)
[![CGL](https://img.shields.io/github/actions/workflow/status/eliashaeussler/deep-closure-comparator/cgl.yaml?label=cgl&logo=github)](https://github.com/eliashaeussler/deep-closure-comparator/actions/workflows/cgl.yaml)
[![Tests](https://img.shields.io/github/actions/workflow/status/eliashaeussler/deep-closure-comparator/tests.yaml?label=tests&logo=github)](https://github.com/eliashaeussler/deep-closure-comparator/actions/workflows/tests.yaml)
[![Supported PHP Versions](https://img.shields.io/packagist/dependency-v/eliashaeussler/deep-closure-comparator/php?logo=php)](https://packagist.org/packages/eliashaeussler/deep-closure-comparator)

</div>

A Composer library that provides a PHPUnit comparator to assert equality
of closures. It can be used to perform deep evaluation of closures, e.g.
as part of objects. Closures are compared using their serialized value,
which is calculated by the [`opis/closure`](https://github.com/opis/closure)
library.

## 🔥 Installation

[![Packagist](https://img.shields.io/packagist/v/eliashaeussler/deep-closure-comparator?label=version&logo=packagist)](https://packagist.org/packages/eliashaeussler/deep-closure-comparator)
[![Packagist Downloads](https://img.shields.io/packagist/dt/eliashaeussler/deep-closure-comparator?color=brightgreen)](https://packagist.org/packages/eliashaeussler/deep-closure-comparator)

```bash
composer require --dev eliashaeussler/deep-closure-comparator
```

## ⚡ Usage

Instead of using the `self::assertEquals()` method of a PHPUnit test case, use the `assertEquals`
method that is shipped within the [`DeepClosureAssert`](src/DeepClosureAssert.php) class:

```php
use EliasHaeussler\DeepClosureComparator\DeepClosureAssert;
use PHPUnit\Framework\TestCase;

final class Foo
{
    public function __construct(
        public ?\Closure $bar = null,
    ) {}
}

final class FooTest extends TestCase
{
    public function testSomething(): void
    {
        $expected = new Foo();
        $expected->bar = static fn() => 'foo';

        $actual = new Foo();
        $actual->bar = static fn() => 'foo';

        DeepClosureAssert::assertEquals($expected, $actual);
    }
}
```

Or, in other words:

```diff
-self::assertEquals($expected, $actual);
+DeepClosureAssert::assertEquals($expected, $actual);
```

> [!NOTE]
> Closures are compared using their serialized representation. This is done by the
> [`opis/closure`](https://github.com/opis/closure) library, which provides mechanisms
> to deep inspect and serialize given closures. More information can be found in the
> [official documentation](https://opis.io/closure) of this library.

## 🧑‍💻 Contributing

Please have a look at [`CONTRIBUTING.md`](CONTRIBUTING.md).

## 💎 Credits

This project developed from a hardened implementation detail in the
[`sebastian/comparator`](https://github.com/sebastianbergmann/comparator/issues/128) library,
as part of PHPUnit's supply chain. With the introduction of a new `ClosureComparator`, comparing
closures got a lot more difficult. Finally, [@tstarling](https://github.com/tstarling) suggested
parts of the actual implementation of this `deep-closure-comparator` library. Thank you very much
for your support!

## ⭐ License

This project is licensed under [GNU General Public License 3.0 (or later)](LICENSE).
