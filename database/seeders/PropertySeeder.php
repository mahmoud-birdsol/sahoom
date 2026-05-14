<?php

namespace Database\Seeders;

use App\Models\Landlord;
use App\Models\Property;
use App\Models\States\LandlordKycStatus;
use App\Models\States\LandlordStatus;
use App\Models\States\PropertyStatus;
use App\Models\States\PropertyType;
use App\Models\States\UserRole;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class PropertySeeder extends Seeder
{
    public function run(): void
    {
        Property::query()->delete();

        $landlordUser = User::firstOrCreate(
            ['email' => 'landlord@sahoom.test'],
            [
                'name'              => 'Kouassi Assoumou',
                'password'          => Hash::make('password'),
                'role'              => UserRole::LANDLORD,
                'is_active'         => true,
                'email_verified_at' => now(),
            ]
        );

        $landlord = Landlord::firstOrCreate(
            ['user_id' => $landlordUser->id],
            [
                'company_name'  => 'Assoumou Immobilier',
                'contact_name'  => 'Kouassi Assoumou',
                'contact_phone' => '+225 07 00 11 22 33',
                'contact_email' => 'landlord@sahoom.test',
                'status'        => LandlordStatus::ACTIVE,
                'kyc_status'    => LandlordKycStatus::APPROVED,
            ]
        );

        $properties = [
            // ── FEATURED — RESIDENTIAL ──────────────────────────────────────
            [
                'title'         => 'Appartement de standing — Cocody Riviera',
                'description'   => 'Superbe appartement meuble de 3 chambres au coeur du quartier residentiel de la Riviera. Terrasse privee, piscine commune, gardiennage 24h/24.',
                'city'          => 'Abidjan',
                'address_line_1'=> 'Riviera 3, Rue des Jardins',
                'size_sqm'      => 140,
                'monthly_rent'  => 450000,
                'property_type' => PropertyType::RESIDENTIAL,
                'is_featured'   => true,
            ],
            [
                'title'         => 'Villa avec piscine — Cocody Les Deux Plateaux',
                'description'   => 'Majestueuse villa de 5 chambres dans le quartier des Deux Plateaux. Piscine privee, jardin paysager, parking 3 voitures et cuisine equipee.',
                'city'          => 'Abidjan',
                'address_line_1'=> 'Les Deux Plateaux, Vallons',
                'size_sqm'      => 380,
                'monthly_rent'  => 1200000,
                'property_type' => PropertyType::RESIDENTIAL,
                'is_featured'   => true,
            ],
            [
                'title'         => 'Studio moderne — Plateau Centre-ville',
                'description'   => 'Studio entierement renove au coeur du Plateau. Connexion fibre, climatisation, securite et acces direct aux transports en commun.',
                'city'          => 'Abidjan',
                'address_line_1'=> 'Avenue Botreau Roussel, Plateau',
                'size_sqm'      => 45,
                'monthly_rent'  => 180000,
                'property_type' => PropertyType::RESIDENTIAL,
                'is_featured'   => true,
            ],
            // ── REGULAR — RESIDENTIAL ───────────────────────────────────────
            [
                'title'         => 'Appartement F3 — Marcory Zone 4',
                'description'   => 'Bel appartement de 3 pieces bien entretenu en Zone 4. Proximitedes commerces, ecoles et de la Lagune Ebrié.',
                'city'          => 'Abidjan',
                'address_line_1'=> 'Zone 4, Boulevard de Marseille',
                'size_sqm'      => 90,
                'monthly_rent'  => 250000,
                'property_type' => PropertyType::RESIDENTIAL,
                'is_featured'   => false,
            ],
            [
                'title'         => 'Maison familiale — Yopougon Selmer',
                'description'   => 'Grande maison de 4 chambres dans un quartier calme de Yopougon. Cour spacieuse, puits, et parking securise.',
                'city'          => 'Abidjan',
                'address_line_1'=> 'Yopougon Selmer, Rue 12',
                'size_sqm'      => 200,
                'monthly_rent'  => 350000,
                'property_type' => PropertyType::RESIDENTIAL,
                'is_featured'   => false,
            ],
            [
                'title'         => 'Appartement meuble — Bouake Centre',
                'description'   => 'Appartement entierement meuble de 2 chambres dans le centre de Bouake. Ideal pour cadres en mobilite ou expatries.',
                'city'          => 'Bouake',
                'address_line_1'=> 'Avenue de la Republique, Centre',
                'size_sqm'      => 75,
                'monthly_rent'  => 150000,
                'property_type' => PropertyType::RESIDENTIAL,
                'is_featured'   => false,
            ],
            // ── REGULAR — COMMERCIAL ────────────────────────────────────────
            [
                'title'         => 'Bureau moderne — Plateau Immeuble Alliance',
                'description'   => 'Espace de bureaux de 230 m2 au 5e etage de l\'Immeuble Alliance. Open space modulable, salle de conference, fibre optique et parking.',
                'city'          => 'Abidjan',
                'address_line_1'=> 'Immeuble Alliance, Avenue Noguès, Plateau',
                'size_sqm'      => 230,
                'monthly_rent'  => 900000,
                'property_type' => PropertyType::COMMERCIAL,
                'is_featured'   => false,
            ],
            [
                'title'         => 'Local commercial — Adjame Grand Marche',
                'description'   => 'Local commercial de 80 m2 en rez-de-chaussee face au Grand Marche d\'Adjame. Fort passage pietier, vitrine sur rue, stockage en arriere-boutique.',
                'city'          => 'Abidjan',
                'address_line_1'=> 'Grand Marche, Adjame',
                'size_sqm'      => 80,
                'monthly_rent'  => 400000,
                'property_type' => PropertyType::COMMERCIAL,
                'is_featured'   => false,
            ],
            [
                'title'         => 'Entrepot logistique — Vridi Zone Industrielle',
                'description'   => 'Entrepot de 600 m2 en zone industrielle de Vridi. Quai de chargement, hauteur sous plafond 7 m, gardiennage et acces poids lourds.',
                'city'          => 'Abidjan',
                'address_line_1'=> 'Zone Industrielle de Vridi, Rue E',
                'size_sqm'      => 600,
                'monthly_rent'  => 700000,
                'property_type' => PropertyType::COMMERCIAL,
                'is_featured'   => false,
            ],
            [
                'title'         => 'Boutique en pied d\'immeuble — San-Pedro Centre',
                'description'   => 'Surface commerciale de 55 m2 en pied d\'immeuble au centre-ville de San-Pedro. Vitrine double, clientele de passage elevee.',
                'city'          => 'San-Pedro',
                'address_line_1'=> 'Avenue du Commerce, Centre-ville',
                'size_sqm'      => 55,
                'monthly_rent'  => 200000,
                'property_type' => PropertyType::COMMERCIAL,
                'is_featured'   => false,
            ],
        ];

        foreach ($properties as $data) {
            Property::create([
                'landlord_id'      => $landlord->id,
                'slug'             => Str::slug($data['title']),
                'title'            => $data['title'],
                'description'      => $data['description'],
                'city'             => $data['city'],
                'address_line_1'   => $data['address_line_1'],
                'address_line_2'   => null,
                'state'            => null,
                'postal_code'      => null,
                'country'          => 'Cote d\'Ivoire',
                'size_sqm'         => $data['size_sqm'],
                'monthly_rent'     => $data['monthly_rent'],
                'currency'         => 'XOF',
                'pricing_type'     => 'monthly',
                'property_type'    => $data['property_type'],
                'status'           => PropertyStatus::APPROVED,
                'is_active'        => true,
                'is_featured'      => $data['is_featured'],
                'latitude'         => null,
                'longitude'        => null,
                'traffic_score'    => rand(5, 10),
                'security_deposit' => $data['monthly_rent'] * 2,
                'min_lease_months' => 6,
                'max_lease_months' => 24,
            ]);
        }

        $featured  = collect($properties)->where('is_featured', true)->count();
        $regular   = collect($properties)->where('is_featured', false)->count();
        $residential = collect($properties)->where('property_type', PropertyType::RESIDENTIAL)->count();
        $commercial  = collect($properties)->where('property_type', PropertyType::COMMERCIAL)->count();

        $this->command->info("Seeded {$featured} featured + {$regular} regular ({$residential} residential, {$commercial} commercial) properties.");
    }
}
