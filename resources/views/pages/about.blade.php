<x-layouts.public title="À propos de Sahoome">
    @push('head')
        <style>
            .sahoome-about { --gold:#B8962E; --gold-light:#D4AE52; --gold-pale:#F5EDD6; --cream:#FAFAF7; --nude:#F4EFE8; --white:#FFFFFF; --ink:#111318; --muted:#7A7D8A; --border:#E8E2D8; font-family:'Montserrat',sans-serif; color:var(--ink); }
            .sahoome-about *, .sahoome-about *::before, .sahoome-about *::after { box-sizing:border-box; }
            .sahoome-about p { margin:0; }
/* ── HERO SOBRE ── */
.sahoome-about .hero{
  padding:160px 6% 100px;
  background:var(--white);
  border-bottom:1px solid var(--border);
  display:grid;
  grid-template-columns:1fr 1fr;
  gap:80px;
  align-items:end;
  max-width:100%;
}
.sahoome-about .hero-left{}
.sahoome-about .hero-eyebrow{display:flex;align-items:center;gap:14px;margin-bottom:22px;}
.sahoome-about .he-line{width:32px;height:1px;background:var(--gold);}
.sahoome-about .he-tag{font-size:.6rem;font-weight:600;letter-spacing:.2em;text-transform:uppercase;color:var(--gold);}
.sahoome-about .hero-h1{
  font-family:'Playfair Display',serif;
  font-size:clamp(2.8rem,5vw,4.6rem);
  font-weight:300;color:var(--ink);
  line-height:1.06;
}
.sahoome-about .hero-h1 em{font-style:italic;color:var(--gold);}
.sahoome-about .hero-right{}
.sahoome-about .hero-right p{
  font-size:.9rem;font-weight:300;
  color:var(--muted);line-height:1.95;
  margin-bottom:20px;
}
.sahoome-about .hero-right p:last-child{margin-bottom:0;}
.sahoome-about .hero-right strong{font-weight:500;color:var(--ink);}

/* ── STATS ── */
.sahoome-about .stats-band{
  display:grid;grid-template-columns:repeat(4,1fr);
  background:var(--ink);
}
.sahoome-about .sb-item{
  padding:52px 32px;text-align:center;
  border-right:1px solid rgba(255,255,255,.08);
  transition:background .3s;
}
.sahoome-about .sb-item:last-child{border-right:none;}
.sahoome-about .sb-item:hover{background:rgba(184,150,46,.1);}
.sahoome-about .sb-val{
  font-family:'Playfair Display',serif;
  font-size:2.8rem;font-weight:300;
  color:var(--gold-light);line-height:1;
}
.sahoome-about .sb-lbl{
  font-size:.58rem;font-weight:600;
  letter-spacing:.16em;text-transform:uppercase;
  color:rgba(255,255,255,.38);margin-top:9px;
}

/* ── VISION ── */
.sahoome-about .vision-sec{
  display:grid;grid-template-columns:1fr 1fr;
  gap:0;
  border-bottom:1px solid var(--border);
}
.sahoome-about .vision-img{
  height:520px;overflow:hidden;
  position:relative;
}
.sahoome-about .vision-img img{width:100%;height:100%;object-fit:cover;transition:transform .8s ease;}
.sahoome-about .vision-img:hover img{transform:scale(1.03);}
.sahoome-about .vision-year{
  position:absolute;bottom:28px;left:28px;
  background:var(--white);
  padding:18px 22px;
}
.sahoome-about .vy-num{font-family:'Playfair Display',serif;font-size:1.6rem;font-weight:300;color:var(--gold);}
.sahoome-about .vy-txt{font-size:.58rem;font-weight:600;letter-spacing:.14em;text-transform:uppercase;color:var(--muted);margin-top:3px;}
.sahoome-about .vision-text{
  padding:80px 70px;
  display:flex;flex-direction:column;justify-content:center;
  background:var(--cream);
}
.sahoome-about .sec-eyebrow{display:flex;align-items:center;gap:14px;margin-bottom:16px;}
.sahoome-about .sey-line{width:26px;height:1px;background:var(--gold);}
.sahoome-about .sey-text{font-size:.58rem;font-weight:600;letter-spacing:.18em;text-transform:uppercase;color:var(--gold);}
.sahoome-about .sec-h2{font-family:'Playfair Display',serif;font-size:clamp(1.7rem,2.8vw,2.6rem);font-weight:300;color:var(--ink);line-height:1.15;margin-bottom:22px;}
.sahoome-about .sec-h2 em{font-style:italic;}
.sahoome-about .vision-text p{font-size:.87rem;font-weight:300;color:var(--muted);line-height:1.95;margin-bottom:16px;}
.sahoome-about .vision-text p:last-child{margin-bottom:0;}

/* ── POSITIONNEMENT ── */
.sahoome-about .position-sec{padding:90px 6%;background:var(--white);border-bottom:1px solid var(--border);}
.sahoome-about .position-inner{max-width:1100px;margin:0 auto;}
.sahoome-about .position-intro{max-width:640px;margin-bottom:64px;}
.sahoome-about .position-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:1px;background:var(--border);}
.sahoome-about .pos-item{background:var(--white);padding:42px 34px;transition:background .3s;}
.sahoome-about .pos-item:hover{background:var(--nude);}
.sahoome-about .pos-line{width:32px;height:2px;background:var(--gold);margin-bottom:22px;transition:width .3s;}
.sahoome-about .pos-item:hover .pos-line{width:56px;}
.sahoome-about .pos-title{font-family:'Playfair Display',serif;font-size:1.2rem;font-weight:400;color:var(--ink);margin-bottom:12px;}
.sahoome-about .pos-title em{font-style:italic;}
.sahoome-about .pos-desc{font-size:.82rem;font-weight:300;color:var(--muted);line-height:1.8;}

/* ── SERVICES ── */
.sahoome-about .services-sec{padding:90px 6%;background:var(--nude);border-bottom:1px solid var(--border);}
.sahoome-about .services-inner{max-width:1100px;margin:0 auto;}
.sahoome-about .services-grid{display:grid;grid-template-columns:repeat(2,1fr);gap:2px;background:var(--border);margin-top:52px;}
.sahoome-about .svc{background:var(--nude);padding:44px 38px;transition:background .3s;display:flex;gap:28px;align-items:flex-start;}
.sahoome-about .svc:hover{background:var(--white);}
.sahoome-about .svc-num{font-family:'Playfair Display',serif;font-size:2rem;font-weight:300;color:var(--gold-pale);line-height:1;flex-shrink:0;transition:color .3s;}
.sahoome-about .svc:hover .svc-num{color:var(--gold-light);}
.sahoome-about .svc-title{font-family:'Playfair Display',serif;font-size:1.2rem;font-weight:400;color:var(--ink);margin-bottom:8px;}
.sahoome-about .svc-title em{font-style:italic;}
.sahoome-about .svc-desc{font-size:.82rem;font-weight:300;color:var(--muted);line-height:1.78;}

/* ── AMBITION ── */
.sahoome-about .ambition-sec{
  background:var(--ink);
  padding:100px 6%;
  display:grid;grid-template-columns:1fr 1fr;
  gap:80px;align-items:center;
}
.sahoome-about .amb-left{}
.sahoome-about .amb-left .sey-line{background:var(--gold-light);}
.sahoome-about .amb-left .sey-text{color:var(--gold-light);}
.sahoome-about .amb-h2{font-family:'Playfair Display',serif;font-size:clamp(1.8rem,3vw,2.8rem);font-weight:300;color:var(--white);line-height:1.15;margin-bottom:22px;}
.sahoome-about .amb-h2 em{font-style:italic;color:var(--gold-light);}
.sahoome-about .amb-p{font-size:.88rem;font-weight:300;color:rgba(255,255,255,.5);line-height:1.9;}
.sahoome-about .amb-right{display:flex;flex-direction:column;gap:2px;}
.sahoome-about .amb-item{
  background:rgba(255,255,255,.04);
  border:1px solid rgba(255,255,255,.07);
  padding:28px 30px;
  transition:background .3s,border-color .3s;
  display:flex;align-items:flex-start;gap:18px;
}
.sahoome-about .amb-item:hover{background:rgba(184,150,46,.1);border-color:rgba(184,150,46,.25);}
.sahoome-about .amb-icon{font-size:1.3rem;flex-shrink:0;margin-top:2px;}
.sahoome-about .amb-item-title{font-size:.8rem;font-weight:600;letter-spacing:.06em;color:var(--white);margin-bottom:5px;}
.sahoome-about .amb-item-desc{font-size:.78rem;font-weight:300;color:rgba(255,255,255,.42);line-height:1.7;}

/* ── ENGAGEMENT ── */
.sahoome-about .engage-sec{padding:90px 6%;background:var(--white);border-top:1px solid var(--border);}
.sahoome-about .engage-inner{max-width:1100px;margin:0 auto;}
.sahoome-about .engage-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:44px;margin-top:56px;}
.sahoome-about .eg-item{border-top:2px solid var(--border);padding-top:28px;transition:border-color .3s;}
.sahoome-about .eg-item:hover{border-color:var(--gold);}
.sahoome-about .eg-num{font-family:'Playfair Display',serif;font-size:.72rem;font-weight:300;color:var(--gold);letter-spacing:.2em;margin-bottom:14px;}
.sahoome-about .eg-title{font-size:.84rem;font-weight:600;letter-spacing:.04em;color:var(--ink);margin-bottom:10px;}
.sahoome-about .eg-desc{font-size:.82rem;font-weight:300;color:var(--muted);line-height:1.78;}

/* ── TESTI ── */
.sahoome-about .testi-sec{background:var(--cream);overflow:hidden;padding:90px 0;}
.sahoome-about .testi-header{padding:0 6%;margin-bottom:48px;}
.sahoome-about .testi-track{display:flex;gap:2px;background:var(--border);animation:sahoome-about-slide 40s linear infinite;width:max-content;}
.sahoome-about .testi-track:hover{animation-play-state:paused;}
@keyframes sahoome-about-slide{from{transform:translateX(0);}to{transform:translateX(-50%);}}
.sahoome-about .tcard{background:var(--white);padding:36px 32px;width:320px;flex-shrink:0;}
.sahoome-about .tcard-stars{color:var(--gold);font-size:.8rem;margin-bottom:12px;letter-spacing:.1em;}
.sahoome-about .tcard-text{font-family:'Playfair Display',serif;font-size:.95rem;font-weight:300;font-style:italic;color:var(--ink);line-height:1.7;margin-bottom:16px;}
.sahoome-about .tcard-author{font-size:.58rem;font-weight:600;letter-spacing:.14em;text-transform:uppercase;color:var(--muted);}

/* ── CTA ── */
.sahoome-about .cta-sec{
  position:relative;height:380px;
  overflow:hidden;display:flex;align-items:center;
}
.sahoome-about .cta-bg{position:absolute;inset:0;background-image:url('https://images.unsplash.com/photo-1486325212027-8081e485255e?w=1600&q=85');background-size:cover;background-position:center;}
.sahoome-about .cta-overlay{position:absolute;inset:0;background:rgba(17,19,24,.72);}
.sahoome-about .cta-content{position:relative;z-index:2;padding:0 6%;}
.sahoome-about .cta-h2{font-family:'Playfair Display',serif;font-size:clamp(1.8rem,3.5vw,3rem);font-weight:300;color:var(--white);line-height:1.1;margin-bottom:30px;}
.sahoome-about .cta-h2 em{font-style:italic;color:var(--gold-light);}
.sahoome-about .cta-btns{display:flex;gap:12px;flex-wrap:wrap;}
.sahoome-about .btn-gold{background:var(--gold);color:var(--white);padding:14px 30px;font-size:.65rem;font-weight:600;letter-spacing:.18em;text-decoration:none;text-transform:uppercase;transition:background .25s;}
.sahoome-about .btn-gold:hover{background:var(--white);color:var(--ink);}
.sahoome-about .btn-ghost{color:rgba(255,255,255,.7);border:1px solid rgba(255,255,255,.3);padding:14px 28px;font-size:.65rem;font-weight:600;letter-spacing:.16em;text-decoration:none;text-transform:uppercase;transition:all .25s;}
.sahoome-about .btn-ghost:hover{background:rgba(255,255,255,.1);color:var(--white);border-color:var(--white);}

@media(max-width:1024px){
  .sahoome-about .hero{grid-template-columns:1fr;gap:40px;padding-top:130px;}
  .sahoome-about .vision-sec{grid-template-columns:1fr;}
  .sahoome-about .vision-img{height:300px;}
  .sahoome-about .vision-text{padding:52px 40px;}
  .sahoome-about .stats-band{grid-template-columns:repeat(2,1fr);}
  .sahoome-about .ambition-sec{grid-template-columns:1fr;}
  .sahoome-about .position-grid{grid-template-columns:1fr 1fr;}
}
@media(max-width:768px){

  .sahoome-about .position-grid,.sahoome-about .services-grid,.sahoome-about .engage-grid{grid-template-columns:1fr;}
  .sahoome-about .stats-band{grid-template-columns:1fr 1fr;}

}

 .sahoome-about .hero { padding-top:80px; }
 @media(max-width:1024px) { .sahoome-about .hero { padding-top:50px; } }
 @media(prefers-reduced-motion:reduce) { .sahoome-about .testi-track { animation:none; width:auto; overflow-x:auto; } }
        </style>
    @endpush
    <x-public.navbar />
    <main class="sahoome-about">
<!-- ── HERO ── -->
<section class="hero">
  <div class="hero-left">
    <div class="hero-eyebrow"><div class="he-line"></div><span class="he-tag">À propos de Sahoome</span></div>
    <h1 class="hero-h1">
      Une nouvelle<br>référence<br>immobilière<br>pour <em>Abidjan</em>
    </h1>
  </div>
  <div class="hero-right">
    <p>Sahoome est une plateforme immobilière digitale fondée à Abidjan en 2024. Notre mission est de <strong>simplifier et sécuriser</strong> l'accès à l'immobilier pour les particuliers, les investisseurs et les professionnels en Côte d'Ivoire.</p>
    <p>Dans un marché en pleine croissance mais encore peu digitalisé, Sahoome apporte <strong>transparence, rigueur et accessibilité</strong> à chaque transaction — qu'il s'agisse d'un espace flexible ou d'une location courte durée.</p>
    <p>Nous construisons la plateforme immobilière que mérite Abidjan.</p>
  </div>
</section>

<!-- ── STATS ── -->
<div class="stats-band">
  <div class="sb-item"><div class="sb-val">350+</div><div class="sb-lbl">Biens disponibles</div></div>
  <div class="sb-item"><div class="sb-val">8</div><div class="sb-lbl">Communes couvertes</div></div>
  <div class="sb-item"><div class="sb-val">1 200+</div><div class="sb-lbl">Transactions réalisées</div></div>
  <div class="sb-item"><div class="sb-val">98%</div><div class="sb-lbl">Taux de satisfaction</div></div>
</div>

<!-- ── VISION ── -->
<section class="vision-sec">
  <div class="vision-img">
    <img src="https://images.unsplash.com/photo-1560185007-cde436f6a4d0?w=900&q=85" alt="Sahoome Vision">
    <div class="vision-year">
      <div class="vy-num">2024</div>
      <div class="vy-txt">Fondée à Abidjan</div>
    </div>
  </div>
  <div class="vision-text">
    <div class="sec-eyebrow"><div class="sey-line"></div><span class="sey-text">Notre vision</span></div>
    <h2 class="sec-h2">Transformer le marché<br>immobilier <em>ivoirien</em></h2>
    <p>Le marché immobilier d'Abidjan est l'un des plus dynamiques d'Afrique de l'Ouest. Portée par une croissance démographique soutenue et une classe moyenne en expansion, la demande immobilière ne cesse d'augmenter.</p>
    <p>Pourtant, ce marché reste marqué par un manque de transparence, une faible digitalisation et des transactions souvent complexes pour les particuliers. Sahoome est née pour répondre à ce défi.</p>
    <p>Notre vision est celle d'un marché immobilier ivoirien moderne, transparent et accessible à tous — où chaque transaction est simple, sécurisée et fiable.</p>
  </div>
</section>

<!-- ── POSITIONNEMENT ── -->
<section class="position-sec">
  <div class="position-inner">
    <div class="position-intro">
      <div class="sec-eyebrow"><div class="sey-line"></div><span class="sey-text">Notre positionnement</span></div>
      <h2 class="sec-h2">Ce qui nous<br><em>distingue</em></h2>
      <p style="font-size:.88rem;font-weight:300;color:var(--muted);line-height:1.9;">Sahoome n'est pas une simple vitrine d'annonces. Nous sommes une plateforme d'accompagnement immobilier intégral — de la recherche à la signature.</p>
    </div>
    <div class="position-grid">
      <div class="pos-item">
        <div class="pos-line"></div>
        <div class="pos-title">Biens <em>vérifiés</em></div>
        <p class="pos-desc">Chaque bien publié sur Sahoome est inspecté physiquement par notre équipe. Nous garantissons l'authenticité et la conformité de chaque annonce.</p>
      </div>
      <div class="pos-item">
        <div class="pos-line"></div>
        <div class="pos-title">Transactions <em>sécurisées</em></div>
        <p class="pos-desc">Paiements protégés, contrats vérifiés, accompagnement personnalisé. Chaque étape est encadrée pour protéger toutes les parties.</p>
      </div>
      <div class="pos-item">
        <div class="pos-line"></div>
        <div class="pos-title">Ancrage <em>local</em></div>
        <p class="pos-desc">Conçu pour la réalité ivoirienne — Mobile Money, droit OHADA, communes d'Abidjan. Une plateforme qui comprend son marché et ses utilisateurs.</p>
      </div>
      <div class="pos-item">
        <div class="pos-line"></div>
        <div class="pos-title">Offre <em>complète</em></div>
        <p class="pos-desc">Espaces flexibles et location courte durée — Sahoome réunit des lieux adaptés à vos projets professionnels et à vos séjours temporaires.</p>
      </div>
      <div class="pos-item">
        <div class="pos-line"></div>
        <div class="pos-title">Accompagnement <em>intégral</em></div>
        <p class="pos-desc">De la première recherche à la remise des clés, notre équipe guide chaque client avec expertise et disponibilité. Réponse garantie sous 24 heures.</p>
      </div>
      <div class="pos-item">
        <div class="pos-line"></div>
        <div class="pos-title">Expérience <em>digitale</em></div>
        <p class="pos-desc">Interface intuitive, recherche avancée, formulaires en ligne, contrats digitaux — une expérience fluide pensée pour les standards actuels.</p>
      </div>
    </div>
  </div>
</section>

<!-- ── SERVICES ── -->
<section class="services-sec">
  <div class="services-inner">
    <div class="">
      <div class="sec-eyebrow"><div class="sey-line"></div><span class="sey-text">Nos activités</span></div>
      <h2 class="sec-h2">Deux services,<br>une seule <em>plateforme</em></h2>
    </div>
    <div class="services-grid">
      <div class="svc">
        <div class="svc-num">01</div>
        <div>
          <div class="svc-title">Location <em>courte durée</em></div>
          <p class="svc-desc">Logements meublés courte durée pour expatriés et séjours temporaires. Flexibilité maximale avec des standards de qualité élevés.</p>
        </div>
      </div>
      <div class="svc">
        <div class="svc-num">02</div>
        <div>
          <div class="svc-title">Espaces <em>flexibles</em></div>
          <p class="svc-desc">Locaux commerciaux flexibles pour pop-up stores, showrooms et événements professionnels. Un concept unique et innovant sur le marché abidjanais.</p>
        </div>
      </div>
    </div>
  </div>
</section>
<!-- ── AMBITION ── -->
<section class="ambition-sec">
  <div class="amb-left">
    <div class="sec-eyebrow"><div class="sey-line" style="background:var(--gold-light);"></div><span class="sey-text" style="color:var(--gold-light);">Notre ambition</span></div>
    <h2 class="amb-h2">Devenir la référence<br>immobilière de<br>l'<em>Afrique de l'Ouest</em></h2>
    <p class="amb-p">Nous construisons aujourd'hui les fondations d'une plateforme qui demain s'étendra à Dakar, Lomé, Cotonou et au-delà. Abidjan est notre point de départ, l'Afrique de l'Ouest est notre horizon.</p>
  </div>
  <div class="amb-right">
    <div class="amb-item">
      <div class="amb-icon">🏙️</div>
      <div>
        <div class="amb-item-title">Abidjan — Phase 1</div>
        <p class="amb-item-desc">Consolidation sur toutes les communes d'Abidjan et développement de notre base propriétaires.</p>
      </div>
    </div>
    <div class="amb-item">
      <div class="amb-icon">🇨🇮</div>
      <div>
        <div class="amb-item-title">Côte d'Ivoire — Phase 2</div>
        <p class="amb-item-desc">Extension vers Bouaké, San Pedro, Yamoussoukro et les principales villes secondaires.</p>
      </div>
    </div>
    <div class="amb-item">
      <div class="amb-icon">🌍</div>
      <div>
        <div class="amb-item-title">UEMOA — Phase 3</div>
        <p class="amb-item-desc">Déploiement dans les marchés immobiliers clés de l'Afrique de l'Ouest francophone.</p>
      </div>
    </div>
  </div>
</section>

<!-- ── ENGAGEMENTS ── -->
<section class="engage-sec">
  <div class="engage-inner">
    <div class="">
      <div class="sec-eyebrow"><div class="sey-line"></div><span class="sey-text">Nos engagements</span></div>
      <h2 class="sec-h2">Des standards<br><em>exigeants</em></h2>
    </div>
    <div class="engage-grid">
      <div class="eg-item">
        <div class="eg-num">— 01</div>
        <div class="eg-title">Vérification systématique</div>
        <p class="eg-desc">Chaque bien est inspecté physiquement avant publication. Nous maintenons un standard de qualité irréprochable sur l'ensemble de notre catalogue.</p>
      </div>
      <div class="eg-item">
        <div class="eg-num">— 02</div>
        <div class="eg-title">Réactivité garantie</div>
        <p class="eg-desc">Toute demande reçoit une réponse de notre équipe en moins de 24 heures ouvrées. Notre disponibilité est un engagement, pas une promesse.</p>
      </div>
      <div class="eg-item">
        <div class="eg-num">— 03</div>
        <div class="eg-title">Sécurité totale</div>
        <p class="eg-desc">Paiements protégés, contrats vérifiés, accompagnement personnalisé. Chaque transaction est encadrée pour protéger l'ensemble des parties impliquées.</p>
      </div>
    </div>
  </div>
</section>

<!-- ── TESTIMONIALS ── -->
<section class="testi-sec">
  <div class="testi-header">
    <div class="sec-eyebrow"><div class="sey-line"></div><span class="sey-text">Ils nous font confiance</span></div>
    <h2 class="sec-h2">Ce que disent<br>nos <em>clients</em></h2>
  </div>
  <div class="testi-track">
    <div class="tcard"><div class="tcard-stars">★★★★★</div><div class="tcard-text">« Appartement trouvé à Cocody en 3 jours. Processus transparent, propriétaire vérifié, accompagnement irréprochable. »</div><div class="tcard-author">— Aminata K., Locataire · Cocody</div></div>
    <div class="tcard"><div class="tcard-stars">★★★★★</div><div class="tcard-text">« Local commercial au Plateau, contrat signé en 24h. Sahoome a transformé notre façon de trouver des espaces. »</div><div class="tcard-author">— Mariam D., Directrice · Plateau</div></div>
    <div class="tcard"><div class="tcard-stars">★★★★★</div><div class="tcard-text">« Mon appartement loué dès la première semaine de mise en ligne. Des locataires sérieux et une équipe efficace. »</div><div class="tcard-author">— Kouadio F., Propriétaire · Marcory</div></div>
    <div class="tcard"><div class="tcard-stars">★★★★★</div><div class="tcard-text">« La seule plateforme immobilière vraiment fiable à Abidjan. Je recommande à tous les expatriés. »</div><div class="tcard-author">— Sophie M., Directrice Régionale · Riviera</div></div>
    <!-- Duplicate for loop -->
    <div class="tcard"><div class="tcard-stars">★★★★★</div><div class="tcard-text">« Appartement trouvé à Cocody en 3 jours. Processus transparent, propriétaire vérifié, accompagnement irréprochable. »</div><div class="tcard-author">— Aminata K., Locataire · Cocody</div></div>
    <div class="tcard"><div class="tcard-stars">★★★★★</div><div class="tcard-text">« Local commercial au Plateau, contrat signé en 24h. Sahoome a transformé notre façon de trouver des espaces. »</div><div class="tcard-author">— Mariam D., Directrice · Plateau</div></div>
    <div class="tcard"><div class="tcard-stars">★★★★★</div><div class="tcard-text">« Mon appartement loué dès la première semaine de mise en ligne. Des locataires sérieux et une équipe efficace. »</div><div class="tcard-author">— Kouadio F., Propriétaire · Marcory</div></div>
    <div class="tcard"><div class="tcard-stars">★★★★★</div><div class="tcard-text">« La seule plateforme immobilière vraiment fiable à Abidjan. Je recommande à tous les expatriés. »</div><div class="tcard-author">— Sophie M., Directrice Régionale · Riviera</div></div>
  </div>
</section>

<!-- ── CTA ── -->
<section class="cta-sec">
  <div class="cta-bg"></div>
  <div class="cta-overlay"></div>
  <div class="cta-content">
    <h2 class="cta-h2">Votre projet<br>commence <em>ici</em></h2>
    <div class="cta-btns">
      <a href="{{ route('properties.index', ['isShortTerm' => 1]) }}" class="btn-gold">Découvrir nos biens</a>
      <a href="{{ route('home') }}#contact" class="btn-ghost">Nous contacter</a>
    </div>
  </div>
</section>


    </main>
    @include('partials.public-footer')
</x-layouts.public>
