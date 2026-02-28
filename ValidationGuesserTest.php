<?php

declare(strict_types=1);

namespace Imran-Ahmed0730\CrudGenerator\Tests\Unit;

use PHPUnit\Framework\TestCase;
use Imran-Ahmed0730\CrudGenerator\Support\ValidationGuesser;

/**
 * Tests that ValidationGuesser produces correct default validation
 * rules based on field name and type.
 */
class ValidationGuesserTest extends TestCase
{
    // ── Type-based rules ─────────────────────────────────────────────────────

    /** @dataProvider typeRuleProvider */
    public function test_guess_by_type(string $type, bool $required, string $expectedFragment): void
    {
        $result = ValidationGuesser::guess('some_field', $type, $required);

        $this->assertStringContainsString($expectedFragment, $result);
    }

    /** @return array<string, array{string, bool, string}> */
    public static function typeRuleProvider(): array
    {
        return [
            'required string'   => ['string',  true,  'required'],
            'nullable string'   => ['string',  false, 'nullable'],
            'string max'        => ['string',  true,  'max:255'],
            'text max'          => ['text',    true,  'max:65535'],
            'integer rule'      => ['integer', true,  'integer'],
            'boolean rule'      => ['boolean', true,  'boolean'],
            'date rule'         => ['date',    true,  'date'],
            'decimal numeric'   => ['decimal', true,  'numeric'],
            'json array'        => ['json',    true,  'array'],
        ];
    }

    // ── Name-based email detection ────────────────────────────────────────────

    public function test_email_field_name_gets_email_rule(): void
    {
        $result = ValidationGuesser::guess('email', 'string', true);

        $this->assertStringContainsString('email', $result);
        $this->assertStringNotContainsString('max:255', $result); // email overrides string path
    }

    public function test_user_email_field_name_gets_email_rule(): void
    {
        $result = ValidationGuesser::guess('user_email', 'string', true);

        $this->assertStringContainsString('email', $result);
    }

    // ── Name-based URL detection ──────────────────────────────────────────────

    public function test_url_field_name_gets_url_rule(): void
    {
        $result = ValidationGuesser::guess('website_url', 'string', true);

        $this->assertStringContainsString('url', $result);
    }

    public function test_website_field_name_gets_url_rule(): void
    {
        $result = ValidationGuesser::guess('website', 'string', false);

        $this->assertStringContainsString('url', $result);
        $this->assertStringContainsString('nullable', $result);
    }

    // ── Required vs nullable prefix ───────────────────────────────────────────

    public function test_required_field_starts_with_required(): void
    {
        $result = ValidationGuesser::guess('title', 'string', true);

        $this->assertStringStartsWith('required', $result);
    }

    public function test_nullable_field_starts_with_nullable(): void
    {
        $result = ValidationGuesser::guess('bio', 'text', false);

        $this->assertStringStartsWith('nullable', $result);
    }
}
