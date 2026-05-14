<?php

namespace Database\Seeders;

use App\Models\Article;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ArticleSeeder extends Seeder
{
    public function run(): void
    {
        Article::truncate();

        $articles = [
            [
                'title'           => "Le marche immobilier d'Abidjan en pleine croissance en 2025",
                'slug'            => 'le-marche-immobilier-abidjan-croissance-2025',
                'category'        => 'Marche',
                'excerpt'         => "La demande en logements atteint un niveau historique, tiree par une classe moyenne en pleine expansion.",
                'body'            => "<p>Le marche immobilier abidjanais connait une dynamique sans precedent. Plus de 12 000 nouvelles transactions ont ete enregistrees au premier trimestre 2025, soit une hausse de 18% par rapport a l'annee precedente.</p><p>Les quartiers de Cocody Riviera, Marcory et Bingerville concentrent la majorite de cette activite, portes par des projets d'infrastructure recents.</p><p>Pour les investisseurs, les rendements locatifs oscillent entre 6 et 9% par an, des chiffres nettement superieurs a ceux observes dans d'autres capitales ouest-africaines.</p>",
                'cover_image_url' => 'https://images.unsplash.com/photo-1560520653-9e0e4c89eb11?w=800&q=80',
                'author'          => 'Equipe Sahoome',
                'published_at'    => now()->subMonths(2),
            ],
            [
                'title'           => "Nouveau decret sur les frais d'agence : ce qui change",
                'slug'            => 'nouveau-decret-frais-agence-ce-qui-change',
                'category'        => 'Reglementation',
                'excerpt'         => "La reforme repartit desormais les frais d'agence entre bailleur et locataire, applicable immediatement.",
                'body'            => "<p>Le decret n 2024-892 du 15 decembre 2024 modifie profondement les regles encadrant les honoraires des agences immobilieres en Cote d'Ivoire. Desormais, le bailleur prend en charge 60% des frais et le locataire 40%, contre une charge integrale pour le locataire auparavant.</p><p>Cette mesure vise a faciliter l'acces au logement pour les menages a revenus intermediaires, segment qui souffrait d'une barriere a l'entree trop elevee.</p><p>Les agences disposent d'un delai de 90 jours pour mettre a jour leurs contrats types. Sahoome a deja integre cette mise a jour dans tous ses formulaires.</p>",
                'cover_image_url' => 'https://images.unsplash.com/photo-1486325212027-8081e485255e?w=800&q=80',
                'author'          => 'Ibrahim Kone',
                'published_at'    => now()->subMonths(5),
            ],
            [
                'title'           => "Quartiers emergents : les zones a plus fort potentiel",
                'slug'            => 'quartiers-emergents-potentiel-investissement',
                'category'        => 'Investissement',
                'excerpt'         => "Les quartiers peripheriques offrent certains des meilleurs rendements locatifs de la metropole.",
                'body'            => "<p>Si Cocody et le Plateau restent les valeurs sures de l'immobilier abidjanais, plusieurs quartiers peripheriques s'imposent progressivement comme des alternatives attractives pour les investisseurs.</p><p>Port-Bouet, a proximite immediate de l'aeroport, voit ses prix augmenter de 12% par an depuis 2023. Bingerville seduit les familles a la recherche d'espaces verts a des prix encore accessibles.</p><p>Enfin, Yope Centre offre des prix d'entree bas et un potentiel de revalorisation significatif dans un horizon de 5 a 7 ans.</p>",
                'cover_image_url' => 'https://images.unsplash.com/photo-1582407947304-fd86f028f716?w=800&q=80',
                'author'          => 'Aminata Diallo',
                'published_at'    => now()->subMonths(3),
            ],
            [
                'title'           => "Location courte duree : opportunites pour les proprietaires",
                'slug'            => 'location-courte-duree-opportunites-proprietaires',
                'category'        => 'Guide',
                'excerpt'         => "Entre tourisme d'affaires et expatries, la location courte duree offre des revenus jusqu'a 40% superieurs.",
                'body'            => "<p>Le marche de la location courte duree en Cote d'Ivoire se structure rapidement. Porte par les flux de cadres expatries et les voyageurs d'affaires, il permet aux proprietaires de generer des revenus jusqu'a 40% superieurs a ceux d'une location classique.</p><p>Les appartements meubles de standing, situes a Cocody ou au Plateau, sont les plus recherches. Un F3 bien equipe peut se louer entre 150 000 et 300 000 XOF la nuit en haute saison.</p><p>Pour reussir dans ce segment, la qualite des photos, la rapidite de reponse et les equipements (Wi-Fi, climatisation) sont des facteurs cles.</p>",
                'cover_image_url' => 'https://images.unsplash.com/photo-1560518883-ce09059eeffa?w=800&q=80',
                'author'          => 'Jean-Claude Brou',
                'published_at'    => now()->subWeeks(6),
            ],
            [
                'title'           => "Comment financer son premier bien immobilier en Cote d'Ivoire",
                'slug'            => 'financer-premier-bien-immobilier-cote-ivoire',
                'category'        => 'Guide',
                'excerpt'         => "Pret immobilier, tontine ou financement participatif : les options disponibles pour primo-accedants.",
                'body'            => "<p>L'acces a la propriete reste un objectif prioritaire pour de nombreux Ivoiriens. Les banques locales (BNI, SGBCI, Ecobank) proposent des prets immobiliers a des taux variant de 8 a 12%, sur des durees de 10 a 20 ans.</p><p>Conditions principales : justificatifs de revenus stables et apport personnel d'au moins 20%.</p><p>Pour ceux qui ne peuvent mobiliser cet apport, les tontines immobilieres et les cooperatives de logement constituent une alternative credible, a condition de bien choisir ses partenaires.</p>",
                'cover_image_url' => 'https://images.unsplash.com/photo-1448630360428-65456885c650?w=800&q=80',
                'author'          => 'Equipe Sahoome',
                'published_at'    => now()->subWeeks(3),
            ],
            [
                'title'           => "Immobilier commercial au Plateau : les bureaux reprennent",
                'slug'            => 'immobilier-commercial-plateau-bureaux-reprennent',
                'category'        => 'Marche',
                'excerpt'         => "Apres deux annees de teletravail, les grands groupes reinvestissent les bureaux du centre d'affaires abidjanais.",
                'body'            => "<p>Le Plateau, coeur economique d'Abidjan, observe depuis debut 2025 une nette reprise de la demande en immobilier de bureau. Les grandes multinationales et les entreprises regionales reinvestissent les immeubles class A.</p><p>Le loyer moyen pour des bureaux de standing varie desormais entre 18 000 et 35 000 XOF par m2/mois, en hausse de 9% sur un an.</p><p>Les proprietaires qui ont renove leurs locaux pendant la periode creuse beneficient aujourd'hui d'un avantage competitif significatif sur le marche.</p>",
                'cover_image_url' => 'https://images.unsplash.com/photo-1497366216548-37526070297c?w=800&q=80',
                'author'          => 'Ibrahim Kone',
                'published_at'    => now()->subWeeks(1),
            ],
        ];

        foreach ($articles as $data) {
            Article::create(array_merge($data, ['is_published' => true]));
        }
    }
}
