 <!-- ======= Top Bar ======= -->
  <div id="topbar" class="d-flex align-items-center fixed-top">
    <div class="container d-flex align-items-center justify-content-center justify-content-md-between">
      <div class="align-items-center d-none d-md-flex">
        <i class="bi bi-clock"></i> Monday - Saturday, 8AM to 10PM
      </div>
      <div class="d-flex align-items-center">
        <i class="bi bi-phone"></i> Call us now +1 5589 55488 55
      </div>
    </div>
  </div>

       <!-- ======= Cta Section ======= -->
    <section id="cta" class="cta">
      <div class="container" data-aos="zoom-in">

        <div class="text-center">
          <h3>In an emergency? Need help now?</h3>
          <p> Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>
          <a class="cta-btn scrollto" href="#appointment">Make an Make an Appointment</a>
        </div>

      </div>
    </section><!-- End Cta Section -->


    <!-- ======= About Us Section ======= -->
    <section id="about" class="about">
      <div class="container" data-aos="fade-up">

        <div class="section-title">
          <h2>About Us</h2>
          <p>Magnam dolores commodi suscipit. Necessitatibus eius consequatur ex aliquid fuga eum quidem. Sit sint consectetur velit. Quisquam quos quisquam cupiditate. Et nemo qui impedit suscipit alias ea. Quia fugiat sit in iste officiis commodi quidem hic quas.</p>
        </div>

        <div class="row">
          <div class="col-lg-6" data-aos="fade-right">
            <img src="temp1/assets/img/about.jpg" class="img-fluid" alt="">
          </div>
          <div class="col-lg-6 pt-4 pt-lg-0 content" data-aos="fade-left">
            <h3>Voluptatem dignissimos provident quasi corporis voluptates sit assumenda.</h3>
            <p class="fst-italic">
              Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore
              magna aliqua.
            </p>
            <ul>
              <li><i class="bi bi-check-circle"></i> Ullamco laboris nisi ut aliquip ex ea commodo consequat.</li>
              <li><i class="bi bi-check-circle"></i> Duis aute irure dolor in reprehenderit in voluptate velit.</li>
              <li><i class="bi bi-check-circle"></i> Ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate trideta storacalaperda mastiro dolore eu fugiat nulla pariatur.</li>
            </ul>
            <p>
              Ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate
              velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in
              culpa qui officia deserunt mollit anim id est laborum
            </p>
          </div>
        </div>

      </div>
    </section><!-- End About Us Section -->


    <!-- ======= Counts Section ======= -->
    <section id="counts" class="counts">
      <div class="container" data-aos="fade-up">

        <div class="row no-gutters">

          <div class="col-lg-3 col-md-6 d-md-flex align-items-md-stretch">
            <div class="count-box">
              <i class="fas fa-user-md"></i>
              <span data-purecounter-start="0" data-purecounter-end="85" data-purecounter-duration="1" class="purecounter"></span>

              <p><strong>Doctors</strong> consequuntur quae qui deca rode</p>
              <a href="#">Find out more &raquo;</a>
            </div>
          </div>

          <div class="col-lg-3 col-md-6 d-md-flex align-items-md-stretch">
            <div class="count-box">
              <i class="far fa-hospital"></i>
              <span data-purecounter-start="0" data-purecounter-end="26" data-purecounter-duration="1" class="purecounter"></span>
              <p><strong>Departments</strong> adipisci atque cum quia aut numquam delectus</p>
              <a href="#">Find out more &raquo;</a>
            </div>
          </div>

          <div class="col-lg-3 col-md-6 d-md-flex align-items-md-stretch">
            <div class="count-box">
              <i class="fas fa-flask"></i>
              <span data-purecounter-start="0" data-purecounter-end="14" data-purecounter-duration="1" class="purecounter"></span>
              <p><strong>Research Lab</strong> aut commodi quaerat. Aliquam ratione</p>
              <a href="#">Find out more &raquo;</a>
            </div>
          </div>

          <div class="col-lg-3 col-md-6 d-md-flex align-items-md-stretch">
            <div class="count-box">
              <i class="fas fa-award"></i>
              <span data-purecounter-start="0" data-purecounter-end="150" data-purecounter-duration="1" class="purecounter"></span>
              <p><strong>Awards</strong> rerum asperiores dolor molestiae doloribu</p>
              <a href="#">Find out more &raquo;</a>
            </div>
          </div>

        </div>

      </div>
    </section><!-- End Counts Section -->





@extends('layouts.app')
@section('title', 'CS-BENIN - Parent Dashboard')

@section('content')
<!-- ======= Hero Section ======= -->
<section id="hero">
    <div id="heroCarousel" data-bs-interval="5000" class="carousel slide carousel-fade" data-bs-ride="carousel">
        <ol class="carousel-indicators" id="hero-carousel-indicators"></ol>
        <div class="carousel-inner" role="listbox">

            <!-- Slide 1 -->
            <div class="carousel-item active" style="background-image: url(temp1/assets/images/vaccin2.jpg)">
                <div class="container">
                    <h2>La santé de votre enfant est notre priorité. <span>CS-BENIN</span></h2>
                    <p>Accédez au carnet de santé numérique de vos enfants et ne manquez jamais une information importante.</p>
                </div>
            </div>

            <!-- Slide 2 -->
            <div class="carousel-item" style="background-image: url(temp1/assets/images/vaccin3.jpg)"></div>

            <!-- Slide 3 -->
            <div class="carousel-item" style="background-image: url(temp1/assets/images/rasbb.jpg)"></div>
        </div>

        <a class="carousel-control-prev" href="#heroCarousel" role="button" data-bs-slide="prev">
            <span class="carousel-control-prev-icon bi bi-chevron-left" aria-hidden="true"></span>
        </a>

        <a class="carousel-control-next" href="#heroCarousel" role="button" data-bs-slide="next">
            <span class="carousel-control-next-icon bi bi-chevron-right" aria-hidden="true"></span>
        </a>
    </div>
</section><!-- End Hero -->

<main id="main">

<!-- ======= Featured Services Section ======= -->
<section id="featured-services" class="featured-services">
    <div class="container" data-aos="fade-up">
        <div class="row">

            <!-- CARD 1 : Suivi Vaccinal -->
            <div class="col-md-6 col-lg-3 d-flex align-items-stretch mb-5 mb-lg-0">
                <div class="icon-box" data-aos="fade-up" data-aos-delay="100">
                    <div class="icon"><i class="fas fa-calendar-check"></i></div>
                    <h4 class="title"><a href="{{ route('parent.vaccines') }}">Suivi des Vaccins</a></h4>
                    <p class="description">Consultez le calendrier vaccinal de votre enfant et soyez sûr de ne manquer aucune date.</p>
                </div>
            </div>

            <!-- CARD 2 : Rappels Automatiques -->
            <div class="col-md-6 col-lg-3 d-flex align-items-stretch mb-5 mb-lg-0">
                <div class="icon-box" data-aos="fade-up" data-aos-delay="200">
                    <div class="icon"><i class="fas fa-bell"></i></div>
                    <h4 class="title"><a href="{{ route('parent.notifications') }}">Rappels & Notifications</a></h4>
                    <p class="description">Recevez des alertes pour les vaccins, rendez-vous médicaux ou événements importants.</p>
                </div>
            </div>

            <!-- CARD 3 : Historique Médical -->
            <div class="col-md-6 col-lg-3 d-flex align-items-stretch mb-5 mb-lg-0">
                <div class="icon-box" data-aos="fade-up" data-aos-delay="300">
                    <div class="icon"><i class="fas fa-file-medical-alt"></i></div>
                    <h4 class="title"><a href="{{ route('parent.medical-history') }}">Historique Médical</a></h4>
                    <p class="description">Accédez à toutes les consultations, traitements et suivis médicaux de vos enfants.</p>
                </div>
            </div>

            <!-- CARD 4 : Accessibilité -->
            <div class="col-md-6 col-lg-3 d-flex align-items-stretch mb-5 mb-lg-0">
                <div class="icon-box" data-aos="fade-up" data-aos-delay="400">
                    <div class="icon"><i class="fas fa-mobile-alt"></i></div>
                    <h4 class="title"><a href="#">Accessible Partout</a></h4>
                    <p class="description">Votre carnet de santé numérique accessible sur smartphone ou ordinateur, où que vous soyez.</p>
                </div>
            </div>

        </div>
    </div>
</section><!-- End Featured Services Section -->

<!-- ======= Counts Section ======= -->
<section id="counts" class="counts">
  <div class="container" data-aos="fade-up">

    <div class="row no-gutters">

      <!-- Enfants suivis -->
      <div class="col-lg-3 col-md-6 d-md-flex align-items-md-stretch">
        <div class="count-box">
          <i class="fas fa-child"></i>
          <span data-purecounter-start="0" data-purecounter-end="120" data-purecounter-duration="1" class="purecounter"></span>
          <p><strong>Enfants suivis</strong> dans notre plateforme</p>
          <a href="{{ route('parent.children') }}">Voir les enfants &raquo;</a>
        </div>
      </div>

      <!-- Vaccins administrés -->
      <div class="col-lg-3 col-md-6 d-md-flex align-items-md-stretch">
        <div class="count-box">
          <i class="fas fa-syringe"></i>
          <span data-purecounter-start="0" data-purecounter-end="350" data-purecounter-duration="1" class="purecounter"></span>
          <p><strong>Vaccins administrés</strong> cette année</p>
          <a href="{{ route('parent.vaccines') }}">Voir les vaccins &raquo;</a>
        </div>
      </div>

      <!-- Rendez-vous médicaux -->
      <div class="col-lg-3 col-md-6 d-md-flex align-items-md-stretch">
        <div class="count-box">
          <i class="fas fa-calendar-check"></i>
          <span data-purecounter-start="0" data-purecounter-end="240" data-purecounter-duration="1" class="purecounter"></span>
          <p><strong>Rendez-vous</strong> programmés pour vos enfants</p>
          <a href="{{ route('parent.appointments') }}">Voir les rendez-vous &raquo;</a>
        </div>
      </div>

      <!-- Rappels envoyés -->
      <div class="col-lg-3 col-md-6 d-md-flex align-items-md-stretch">
        <div class="count-box">
          <i class="fas fa-bell"></i>
          <span data-purecounter-start="0" data-purecounter-end="500" data-purecounter-duration="1" class="purecounter"></span>
          <p><strong>Rappels</strong> envoyés aux parents</p>
          <a href="{{ route('parent.notifications') }}">Voir les notifications &raquo;</a>
        </div>
      </div>

    </div>

  </div>
</section><!-- End Counts Section -->

<!-- ======= FAQ Section ======= -->
<section id="faq" class="faq section-bg">
    <div class="container" data-aos="fade-up">
        <div class="section-title">
            <h2>Questions Fréquentes</h2>
        </div>
        <ul class="faq-list">
            <li>
                <div data-bs-toggle="collapse" class="collapsed question" href="#faq1">
                    Comment ajouter un nouvel enfant dans le carnet de santé ? 
                    <i class="bi bi-chevron-down icon-show"></i><i class="bi bi-chevron-up icon-close"></i>
                </div>
                <div id="faq1" class="collapse" data-bs-parent=".faq-list">
                    <p>Accédez à votre tableau de bord parent, cliquez sur "Ajouter un enfant" et remplissez les informations nécessaires.</p>
                </div>
            </li>
            <li>
                <div data-bs-toggle="collapse" class="collapsed question" href="#faq2">
                    Puis-je recevoir des rappels par SMS ou email ? 
                    <i class="bi bi-chevron-down icon-show"></i><i class="bi bi-chevron-up icon-close"></i>
                </div>
                <div id="faq2" class="collapse" data-bs-parent=".faq-list">
                    <p>Oui, vous pouvez configurer vos notifications dans les paramètres pour recevoir des alertes par email et/ou SMS.</p>
                </div>
            </li>
            <li>
                <div data-bs-toggle="collapse" class="collapsed question" href="#faq3">
                    Mes données sont-elles sécurisées ? 
                    <i class="bi bi-chevron-down icon-show"></i><i class="bi bi-chevron-up icon-close"></i>
                </div>
                <div id="faq3" class="collapse" data-bs-parent=".faq-list">
                    <p>Toutes les données sont cryptées et protégées. Seuls vous et les professionnels de santé autorisés y avez accès.</p>
                </div>
            </li>
        </ul>
    </div>
</section><!-- End FAQ Section -->

</main>
@endsection