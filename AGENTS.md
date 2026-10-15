# AGENTS.md — dejwcake/sms-client

Framework-agnostic SMS client with swappable drivers. Composer `dejwcake/sms-client`, namespace
`DejwCake\SmsClient` (fork of `matthewbdaly/sms-client`, old namespace dropped). Consumed by
`dejwcake/laravel-sms` — see README.md for usage and per-driver message formats.

## Layout

- `Client` wraps one `Contracts\Driver`: `send(array $message)` → `Driver::sendRequest()`,
  `getDriver()` returns the driver name. `Contracts\Client` is what consumers type against.
- `src/Drivers/` — one `final readonly` class per provider: `Clockwork`, `Nexmo`, `TextLocal`,
  `Twilio`, `O2SK`, `Aws` (SNS, needs `aws/aws-sdk-php`), `Mail` (mail-to-SMS gateway through
  `Contracts\Mailer`), plus test drivers `Log`, `NullDriver`, `RequestBin`.
- HTTP drivers take a Guzzle `ClientInterface` and a config array; the constructor validates the
  config and throws `DriverNotConfiguredException` when a key is missing.
- Guzzle exceptions are translated to the package's own `src/Exceptions/` (`ClientException`,
  `ServerException`, `ConnectException`, `RequestException`) — never leak Guzzle types.
- `PHPMailAdapter` is the plain-PHP `Contracts\Mailer`; laravel-sms ships the Laravel one.

## Commands

Everything runs in Docker from the package root — never against a host PHP. The full,
copy-pasteable list (composer, every QA tool and
the "whole PHP suite" one-liner) is in **README.md → "How to develop this project"**.
The ones you need most:

```shell
docker compose run --rm test composer update
docker compose run --rm test ./vendor/bin/phpunit
docker compose run --rm php-qa phpcs -s --colors --extensions=php
docker compose run --rm php-qa phpcbf -s --colors --extensions=php       # auto-fix style
docker compose run --rm php-qa phpstan analyse --configuration=phpstan.neon
docker compose run --rm php-qa phpmd analyze
docker compose run --rm php-qa phpcs --standard=.phpcs.compatibility.xml --cache=.phpcs.cache
docker compose run --rm php-qa composer normalize
```

A change is done when phpcs, phpstan, phpmd and the test suite are green. CI runs the tests on
every supported PHP version (8.4, 8.5).

## Code conventions

- PHP `^8.4` — code must run on 8.4, so nothing 8.5-only. Plain PHP: **no Laravel dependency**,
  keep it that way. Every file starts with `declare(strict_types=1);`.
- **No helpers, no Facades** — dependencies come in through the constructor.
- Constructor property promotion. `final` classes and `readonly` wherever possible — prefer a
  `final readonly class`, otherwise readonly properties. A readonly property is public rather than
  hidden behind a getter.
- Always import with `use`; never inline `\Fully\Qualified\Names`. Alias colliding names
  (`GuzzleHttp\ClientInterface as GuzzleClient`, Guzzle exceptions as `Guzzle*Exception`).
- Name a property after its type, not `$service`.
- Build strings with `sprintf()` — no `"{$var}"` interpolation and no `.` concatenation.
- Typed constants (`private const string ENDPOINT = …`), `match` over `switch`.
- Mark overrides with `#[Override]` — **except** a method that overrides a *trait* method:
  PHP 8.5.3 segfaults on that.
- Fix new phpstan/phpmd findings in code. Baselines are for accepted, existing debt only — inspect
  the baseline diff before committing it.

## Testing conventions

- PHPUnit 12, no Testbench. Test namespaces mirror `src/` (`tests/Unit/Drivers/TwilioTest.php`).
- Several tested methods of one class → a directory named after the class with one
  `<Method>Test.php` per method (new tests; existing driver tests are one file per class).
- Assertions are static: `self::assert*()`. Mark the subject with `#[CoversClass]`.
- HTTP is faked with Guzzle's `MockHandler` + `HandlerStack` — no real requests. Use
  `createStub()` when nothing is verified, `createMock()` only for expectations.
- Test doubles live in `tests/Support/` (`SpySnsClient`).

## Versioning

The package is on **3.x**: work happens on `v3`, and `main` is fast-forwarded to it once CI is
green. `main` is protected — required checks on the exact commit, no direct pushes. A change that
breaks consumers (driver config keys, contracts, exception types) needs a major version and a
matching update in `dejwcake/laravel-sms`.
