<?php

namespace Database\Factories;

use App\Models\Article;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @template TModel of \App\Models\Article
 *
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<TModel>
 */
class ArticleFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var class-string<TModel>
     */
    protected $model = Article::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $title = fake()->randomElement([
            "Le marché immobilier d'Abidjan en pleine croissance en 2025",
            "Nouveau décret sur les frais d'agence : ce qui change",
            "Les quartiers émergents avec le plus fort potentiel d'investissement",
            "Location courte durée : opportunités pour les propriétaires ivoiriens",
            "Comment financer son premier bien immobilier en Côte d'Ivoire",
            "Zones franches et immobilier commercial : le boom de Plateau",
        ]);

        $slug = \Illuminate\Support\Str::slug($title) . '-' . fake()->unique()->randomNumber(4);

        return [
            'title'           => $title,
            'slug'            => $slug,
            'category'        => fake()->randomElement(['Marché', 'Réglementation', 'Investissement', 'Guide', 'Actualité']),
            'excerpt'         => fake()->sentence(20),
            'body'            => implode("\n\n", fake()->paragraphs(6)),
            'cover_image_path' => null,
            'author'          => fake()->randomElement(['Équipe Sahoome', 'Ibrahim Koné', 'Aminata Diallo', 'Jean-Claude Brou']),
            'is_published'    => true,
            'published_at'    => fake()->dateTimeBetween('-18 months', 'now'),
        ];
    }

    public function draft(): static
    {
        return $this->state(['is_published' => false, 'published_at' => null]);
    }
}
