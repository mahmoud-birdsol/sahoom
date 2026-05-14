<?php

namespace Tests\Feature;

use App\Models\Article;
use App\Models\Property;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ArticleTest extends TestCase
{
    use RefreshDatabase;

    public function test_article_list_page_loads(): void
    {
        Article::factory()->count(3)->create();

        $this->get(route('articles.index'))->assertOk();
    }

    public function test_article_list_only_shows_published(): void
    {
        Article::factory()->create(['is_published' => true, 'title' => 'Published Article', 'slug' => 'published-article']);
        Article::factory()->draft()->create(['title' => 'Draft Article', 'slug' => 'draft-article']);

        $this->get(route('articles.index'))
            ->assertOk()
            ->assertSee('Published Article')
            ->assertDontSee('Draft Article');
    }

    public function test_article_show_page_loads(): void
    {
        $article = Article::factory()->create();

        $this->get(route('articles.show', $article->slug))
            ->assertOk()
            ->assertSee($article->title);
    }

    public function test_article_show_returns_404_for_draft(): void
    {
        $article = Article::factory()->draft()->create();

        $this->get(route('articles.show', $article->slug))->assertNotFound();
    }

    public function test_article_show_returns_404_for_nonexistent_slug(): void
    {
        $this->get(route('articles.show', 'no-such-article'))->assertNotFound();
    }

    public function test_article_published_scope(): void
    {
        Article::factory()->count(2)->create(['is_published' => true]);
        Article::factory()->draft()->count(3)->create();

        $this->assertCount(2, Article::published()->get());
    }

    public function test_property_is_short_term_defaults_to_false(): void
    {
        $property = Property::factory()->create();

        $this->assertFalse((bool) $property->is_short_term);
    }

    public function test_property_is_short_term_can_be_set(): void
    {
        $property = Property::factory()->create(['is_short_term' => true]);

        $this->assertTrue($property->fresh()->is_short_term);
    }

    public function test_property_short_term_filter_query(): void
    {
        Property::factory()->create(['is_short_term' => true]);
        Property::factory()->count(2)->create(['is_short_term' => false]);

        $this->assertEquals(1, Property::where('is_short_term', true)->count());
        $this->assertEquals(2, Property::where('is_short_term', false)->count());
    }
}
