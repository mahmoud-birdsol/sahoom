<?php

namespace App\Livewire;

use App\Models\Article;
use Livewire\Component;

class ArticleShow extends Component
{
    public Article $article;

    public function mount(string $slug): void
    {
        $this->article = Article::query()
            ->published()
            ->where('slug', $slug)
            ->firstOrFail();
    }

    public function render(): \Illuminate\View\View
    {
        $related = Article::query()
            ->published()
            ->where('id', '!=', $this->article->id)
            ->where('category', $this->article->category)
            ->orderByDesc('published_at')
            ->limit(3)
            ->get();

        return view('livewire.article-show', [
            'related' => $related,
        ])->layout('components.layouts.public', ['title' => $this->article->title]);
    }
}
