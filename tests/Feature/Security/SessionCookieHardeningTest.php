<?php

namespace Tests\Feature\Security;

use Symfony\Component\Process\Process;
use Tests\TestCase;

/**
 * SEC-005 / CONF-01 regression tests.
 *
 * `config/session.php` set `'secure' => env('SESSION_SECURE_COOKIE')` with no
 * default, so an unset variable resolved to null and the session cookie was
 * emitted without the Secure flag. Combined with `APP_ENV=local` and
 * `APP_DEBUG=true` being the shipped defaults, a VPS deployment that forgot to
 * override them would render stack traces, environment variables and database
 * credentials on any error page, while leaking session tokens over plain HTTP.
 *
 * The flag now defaults to true whenever APP_ENV=production, so the safe
 * behaviour no longer depends on remembering to set it.
 */
class SessionCookieHardeningTest extends TestCase
{
    /**
     * Resolve the session config in a fresh PHP process.
     *
     * Config files are evaluated exactly once, when the application boots, so
     * the `APP_ENV=production` branch cannot be exercised in-process: this
     * suite boots with APP_ENV=testing (see phpunit.xml). Booting a child
     * process is the only honest way to prove the env-aware default resolves
     * the way it claims to.
     *
     * @param  array<string, string>  $env
     * @return array{env: string, secure: bool|null, debug: bool}
     */
    private function resolveSessionConfig(array $env): array
    {
        $script = <<<'PHP'
        require 'vendor/autoload.php';
        $app = require 'bootstrap/app.php';
        $app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();
        echo json_encode([
            'env' => config('app.env'),
            'secure' => config('session.secure'),
            'debug' => (bool) config('app.debug'),
        ]);
        PHP;

        $process = new Process(
            [PHP_BINARY, '-r', $script],
            base_path(),
            // Explicit values beat both the inherited environment and the
            // local .env file (Dotenv is immutable and will not overwrite an
            // existing variable), which is what makes this deterministic.
            $env,
        );

        $process->run();

        $this->assertTrue(
            $process->isSuccessful(),
            'Booting the application in a child process failed: '.$process->getErrorOutput()
        );

        return json_decode($process->getOutput(), true, 512, JSON_THROW_ON_ERROR);
    }

    public function test_session_secure_flag_is_never_null(): void
    {
        // The original defect: env('SESSION_SECURE_COOKIE') with no default
        // yielded null, which Symfony treats as "no Secure attribute".
        $this->assertIsBool(
            config('session.secure'),
            'config(session.secure) must always resolve to a boolean, never null.'
        );
    }

    public function test_session_cookie_is_http_only_and_same_site_lax(): void
    {
        $response = $this->get('/login');
        $response->assertOk();

        $cookie = $response->getCookie(config('session.cookie'), decrypt: false);

        $this->assertNotNull($cookie, 'The session cookie was not emitted at all.');
        $this->assertTrue($cookie->isHttpOnly(), 'Session cookie must be HttpOnly.');
        $this->assertSame('lax', $cookie->getSameSite(), 'Session cookie must be SameSite=Lax.');
    }

    public function test_session_cookie_carries_the_secure_flag_when_enabled(): void
    {
        config(['session.secure' => true]);

        $response = $this->get('/login');
        $response->assertOk();

        $cookie = $response->getCookie(config('session.cookie'), decrypt: false);

        $this->assertNotNull($cookie, 'The session cookie was not emitted at all.');
        $this->assertTrue($cookie->isSecure(), 'Session cookie must carry the Secure flag.');
        $this->assertTrue($cookie->isHttpOnly(), 'Session cookie must stay HttpOnly.');
        $this->assertSame('lax', $cookie->getSameSite(), 'Session cookie must stay SameSite=Lax.');
    }

    public function test_production_defaults_to_a_secure_cookie(): void
    {
        $resolved = $this->resolveSessionConfig(['APP_ENV' => 'production']);

        $this->assertSame('production', $resolved['env']);
        $this->assertTrue(
            $resolved['secure'],
            'APP_ENV=production must default SESSION_SECURE_COOKIE to true.'
        );
    }

    public function test_local_keeps_the_cookie_usable_over_plain_http(): void
    {
        // Regression guard for developer experience: forcing Secure on
        // http://localhost would silently drop every session and break login.
        $resolved = $this->resolveSessionConfig(['APP_ENV' => 'local']);

        $this->assertSame('local', $resolved['env']);
        $this->assertFalse($resolved['secure']);
    }

    public function test_explicit_env_value_overrides_the_default(): void
    {
        $resolved = $this->resolveSessionConfig([
            'APP_ENV' => 'production',
            'SESSION_SECURE_COOKIE' => 'false',
        ]);

        $this->assertFalse(
            $resolved['secure'],
            'An explicit SESSION_SECURE_COOKIE=false must still win over the default.'
        );
    }

    public function test_production_template_disables_debug_output(): void
    {
        // The other half of CONF-01: with APP_DEBUG=true any error page dumps
        // env vars, the APP_KEY and database credentials to the visitor.
        $resolved = $this->resolveSessionConfig([
            'APP_ENV' => 'production',
            'APP_DEBUG' => 'false',
        ]);

        $this->assertFalse(
            $resolved['debug'],
            'The production profile must not render debug error pages.'
        );
    }

    public function test_production_template_documents_the_hardening(): void
    {
        $path = base_path('.env.production.example');

        $this->assertFileExists(
            $path,
            '.env is gitignored, so the production values need a tracked template.'
        );

        $template = (string) file_get_contents($path);

        $this->assertStringContainsString('APP_ENV=production', $template);
        $this->assertStringContainsString('APP_DEBUG=false', $template);
        $this->assertStringContainsString('SESSION_SECURE_COOKIE=true', $template);

        // Signed order URLs (SEC-002) are generated from APP_URL, so the
        // template must not advertise a plain-http origin.
        $this->assertMatchesRegularExpression('/^APP_URL=https:\/\//m', $template);
    }
}
