<?php

declare(strict_types=1);

namespace YourVendor\CrudGenerator\Tests\Unit;

use PHPUnit\Framework\TestCase;
use YourVendor\CrudGenerator\Support\FieldDefinition;

/**
 * Tests for FieldDefinition value object:
 * casting, HTML input mapping, label generation,
 * and update-rule "sometimes" injection.
 */
class FieldDefinitionTest extends TestCase
{
    // ── label() ──────────────────────────────────────────────────────────────

    public function test_label_converts_snake_case_to_title_case(): void
    {
        $field = new FieldDefinition('published_at', 'datetime', true, 'nullable|date');

        $this->assertSame('Published At', $field->label());
    }

    public function test_label_for_single_word(): void
    {
        $field = new FieldDefinition('title', 'string', false, 'required|string|max:255');

        $this->assertSame('Title', $field->label());
    }

    // ── isRequired / nullable ─────────────────────────────────────────────────

    public function test_is_required_is_opposite_of_nullable(): void
    {
        $required = new FieldDefinition('title', 'string', false, 'required|string');
        $nullable = new FieldDefinition('bio',   'text',   true,  'nullable|string');

        $this->assertTrue($required->isRequired());
        $this->assertFalse($nullable->isRequired());
    }

    // ── htmlInputType() ──────────────────────────────────────────────────────

    /** @dataProvider inputTypeProvider */
    public function test_html_input_type(string $dbType, string $expectedHtml): void
    {
        $field = new FieldDefinition('field', $dbType, false, '');

        $this->assertSame($expectedHtml, $field->htmlInputType());
    }

    /** @return array<string, array{string, string}> */
    public static function inputTypeProvider(): array
    {
        return [
            'string → text'       => ['string',     'text'],
            'text → text'         => ['text',        'text'],
            'integer → number'    => ['integer',     'number'],
            'bigInteger → number' => ['bigInteger',  'number'],
            'decimal → number'    => ['decimal',     'number'],
            'float → number'      => ['float',       'number'],
            'boolean → checkbox'  => ['boolean',     'checkbox'],
            'date → date'         => ['date',        'date'],
            'datetime → datetime-local' => ['datetime', 'datetime-local'],
            'json → text'         => ['json',        'text'],
        ];
    }

    // ── isTextarea / isCheckbox ───────────────────────────────────────────────

    public function test_is_textarea_only_for_text_type(): void
    {
        $this->assertTrue((new FieldDefinition('bio', 'text', true, ''))->isTextarea());
        $this->assertFalse((new FieldDefinition('bio', 'string', true, ''))->isTextarea());
    }

    public function test_is_checkbox_only_for_boolean_type(): void
    {
        $this->assertTrue((new FieldDefinition('active', 'boolean', false, ''))->isCheckbox());
        $this->assertFalse((new FieldDefinition('active', 'integer', false, ''))->isCheckbox());
    }

    // ── castType() ───────────────────────────────────────────────────────────

    /** @dataProvider castTypeProvider */
    public function test_cast_type(?string $expected, string $dbType): void
    {
        $field = new FieldDefinition('field', $dbType, false, '');

        $this->assertSame($expected, $field->castType());
    }

    /** @return array<string, array{string|null, string}> */
    public static function castTypeProvider(): array
    {
        return [
            'boolean'    => ['boolean',  'boolean'],
            'integer'    => ['integer',  'integer'],
            'bigInteger' => ['integer',  'bigInteger'],
            'decimal'    => ['float',    'decimal'],
            'float'      => ['float',    'float'],
            'date'       => ['date',     'date'],
            'datetime'   => ['datetime', 'datetime'],
            'json'       => ['array',    'json'],
            'string'     => [null,       'string'],
            'text'       => [null,       'text'],
        ];
    }

    // ── updateValidation() ───────────────────────────────────────────────────

    public function test_update_validation_prepends_sometimes_to_required_rules(): void
    {
        $field = new FieldDefinition('title', 'string', false, 'required|string|max:255');

        $this->assertSame('sometimes|required|string|max:255', $field->updateValidation());
    }

    public function test_update_validation_prepends_sometimes_to_nullable_rules(): void
    {
        $field = new FieldDefinition('bio', 'text', true, 'nullable|string');

        $this->assertSame('sometimes|nullable|string', $field->updateValidation());
    }

    public function test_update_validation_does_not_double_prepend_sometimes(): void
    {
        $field = new FieldDefinition('title', 'string', false, 'sometimes|required|string');

        // Must not become "sometimes|sometimes|required|string"
        $this->assertSame('sometimes|required|string', $field->updateValidation());
    }

    // ── fromArray() ──────────────────────────────────────────────────────────

    public function test_from_array_factory(): void
    {
        $field = FieldDefinition::fromArray([
            'name'       => 'slug',
            'type'       => 'string',
            'nullable'   => false,
            'validation' => 'required|string|max:255|unique:posts,slug',
        ]);

        $this->assertSame('slug', $field->name);
        $this->assertSame('string', $field->type);
        $this->assertFalse($field->nullable);
        $this->assertSame('required|string|max:255|unique:posts,slug', $field->validation);
    }
}
