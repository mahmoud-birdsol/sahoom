<?php

namespace App\Livewire;

use App\Models\Article;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class ArticleList extends Component
{
    use WithPagination;

    #[Url]
    public string $category = '';

    #[Url]
    public string $search = '';

    public function updatingSearch(): void { $this->resetPage(); }
    public function updatingCategory(): void { $this->resetPage(); }

    public function render(): \Illuminate\View\View
    {
        $query = Article::query()->published()->orderByDesc('published_at');

        if ($this->search !== '') {
            $query->where(function ($q) {
                $q->where('title', 'like', "%{$this->search}%")
                  ->orWhere('excerpt', 'like', "%{$this->search}%");
            });
        }

        if ($this->category !== '') {
            $query->where('category', $this->category);
        }

        $articles   = $query->paginate(9);
        $categories = Article::published()->distinct()->orderBy('category')->pluck('category');

        return view('livewire.article-list', [
            'articles'   => $articles,
            'categories' => $categories,
        ])->layout('components.layouts.public', ['title' => __('Articles & Guides')]);
    }
}
