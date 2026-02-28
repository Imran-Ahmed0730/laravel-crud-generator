<?php

declare(strict_types=1);

namespace YourVendor\CrudGenerator\Tests\Unit;

use PHPUnit\Framework\TestCase;
use YourVendor\CrudGenerator\Support\NameHelper;

/**
 * Tests that NameHelper correctly derives all name forms
 * from various input styles (StudlyCase, snake_case, etc.).
 */
class NameHelperTest extends TestCase
{
    // ── StudlyCase input ─────────────────────────────────────────────────────

    public function test_derives_all_forms_from_studly_input(): void
    {
        $helper = new NameHelper('BlogPost');

        $this->assertSame('BlogPost',  $helper->studly);
        $this->assertSame('blogPost',  $helper->camel);
        $this->assertSame('blog_post', $helper->snake);
        $this->assertSame('blog_posts', $helper->pluralSnake);
        $this->assertSame('BlogPosts',  $helper->pluralStudly);
        $this->assertSame('blog-post',  $helper->kebab);
        $this->assertSame('blog-posts', $helper->pluralKebab);
        $this->assertSame('Blog Post',  $helper->title);
        $this->assertSame('blog_posts', $helper->tableName);
    }

    // ── snake_case input ─────────────────────────────────────────────────────

    public function test_derives_all_forms_from_snake_input(): void
    {
        $helper = new NameHelper('product_category');

        $this->assertSame('ProductCategory',  $helper->studly);
        $this->assertSame('productCategory',  $helper->camel);
        $this->assertSame('product_category', $helper->snake);
        $this->assertSame('product_categories', $helper->pluralSnake);
        $this->assertSame('ProductCategories',  $helper->pluralStudly);
        $this->assertSame('product-category',   $helper->kebab);
        $this->assertSame('product-categories', $helper->pluralKebab);
        $this->assertSame('Product Category',   $helper->title);
    }

    // ── Single word ──────────────────────────────────────────────────────────

    public function test_derives_all_forms_for_single_word(): void
    {
        $helper = new NameHelper('Post');

        $this->assertSame('Post',  $helper->studly);
        $this->assertSame('post',  $helper->camel);
        $this->assertSame('post',  $helper->snake);
        $this->assertSame('posts', $helper->pluralSnake);
        $this->assertSame('Posts', $helper->pluralStudly);
        $this->assertSame('post',  $helper->kebab);
        $this->assertSame('posts', $helper->pluralKebab);
        $this->assertSame('Post',  $helper->title);
    }

    // ── tableName alias ──────────────────────────────────────────────────────

    public function test_table_name_equals_plural_snake(): void
    {
        $helper = new NameHelper('OrderItem');

        $this->assertSame($helper->pluralSnake, $helper->tableName);
    }
}
