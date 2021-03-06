<?php $this->title = "EsMater Accueil"; ?>
<nav class="main-nav">
    <div class="menu-icon">
        <i class="fa fa-bars fa-2x"></i>
    </div>
    <div class="logo">
        <a href="index.php">EsMater</a>
    </div>
    <div class="menu">
        <ul>
            <li><a href="index.php?action=linkView&swicthTo=Presentation">Qui suis-je</a></li>
            <li><a href="index.php?action=page&number=1">Les activités</a></li>
            <?php if(isset($_SESSION["auth"])): ?>
            <li><a href="<?= "index.php?action=toAccount" ?>">Espace personnel</a></li>
            <?php else: ?>
            <li><a href="<?= "index.php?action=linkView&swicthTo=Login" ?>">Se connecter</a></li>
            <li><a href="<?= "index.php?action=linkView&swicthTo=Register" ?>">Crée un compte</a></li>
            <?php endif; ?>
            <li><a class="to-contact-section" href="#contact">Contact</a></li>
        </ul>
    </div>
</nav>

<div class="main-header">
    <div class="header-text">
        <h1>EsMater</h1>
        <p>Patricia Mareau</p>
        <p>Facilitatrice en communication</p>
    </div>
</div>

<div class="team">
    <div class="container">

        <div class="row">
            <div class="col-lg-12 col-lg-offset-3 text-center">
                <h2>Qui suis-je</h2>
                <p>Facilitatrice en communication depuis 2007</p>
            </div>
        </div>

        <div class="row text-center">

            <div class="col-sm-12 col-xs-12">
                <!--remplacer la photo avec le compte de patricia et un lien pour son profile -->
                <a class="team-member" href="index.php?action=linkView&swicthTo=Presentation">
                    <img class="team-img rounded-circle" alt="team-photo" src="src/Content/images/web/patricia.jpg">
                </a>
                <div class="text-member">
                    <h4>Patricia Mareau</h4>
                    <p>Vous pouvez aussi me retrouver sur Facebook et Instagram</p>
                </div>
                <p class="social">
                    <a href="https://www.facebook.com/patricia.mareau.37"><img class="reseau facebook" src="src/Content/images/web/facebook.png" alt=""></a>
                    <a href="https://www.instagram.com/mareaupatricia/?hl=fr"><img class="reseau instagram" src="src/Content/images/web/instagram.png" alt=""></a>
                </p>
            </div>
        </div>

    </div>

</div>

<div class="workshop-section">
    <div class="row">
        <div class="main-text-workshop col-lg-12 col-lg-offset-3 text-center">
            <h2>Les Ateliers</h2>
            <p>Vous trouverez ici la présentation des différents ateliers que je propose</p><br>
        </div>
    </div>
    <div class="workshop-home">
        <div class="row">
            <div class="col-lg-6">
                <a class="workshop-link" href="index.php?action=linkView&swicthTo=Communication">
                    <div class="workshop">
                        <div class="workshop-img workshop-1"></div>
                        <div class="workshop-text">
                            <h4>Communication Bienveillante</h4>
                            <p class="text-muted">Communication non violente</p>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-lg-6">
                <a class="workshop-link" href="index.php?action=linkView&swicthTo=Feminity">
                    <div class="workshop">
                        <div class="workshop-img workshop-2"></div>
                        <div class="workshop-text">
                            <h4>Atelier Féminité</h4>
                            <p class="text-muted">Atelier sur le féminin se comprendre</p>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-lg-6">
                <a class="workshop-link" href="index.php?action=linkView&swicthTo=Rebozo">
                    <div class="workshop">
                        <div class="workshop-img workshop-3"></div>
                        <div class="workshop-text">
                            <h4>Soin Rébozo</h4>
                            <p class="text-muted">Bien être massage</p>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-lg-6">
                <a class="workshop-link" href="index.php?action=linkView&swicthTo=VisionBoard">
                    <div class="workshop">
                        <div class="workshop-img workshop-4"></div>
                        <div class="workshop-text">
                            <h4>Vision Board</h4>
                            <p class="text-muted">Ordonnée prise en main</p>
                        </div>
                    </div>
                </a>
            </div>
        </div>
    </div>
</div>
<?php if($post) : ?>
<div class="last-post">
    <div class="container">
        <div class="row text-last-post">
            <div class="col-lg-12 col-lg-offset-3 text-center">
                <h2>Dernière Activité</h2>
                <p>Vous retrouvez ici la dernière activité proposée, vous pouvez vous y inscrire également</p><br>
            </div>
        </div>
        <div class="row">
            <a class="style-link-none last-post-link" href="<?= "index.php?action=post&id=" . $post['id'] ?>">
                <div class="col-lg-12 mb-12">
                    <div class="posts">
                        <img class="img-posts" src="src/Content/images/posts/<?= $post['img'] ?>" alt="">
                        <div class="card-body">
                            <div class="row">
                                <img class="rounded-circle avatar-posts" src="src/Content/images/avatars/<?= $post['user_avatar'] ?>" alt="avatar">
                                <div class="row style-link-none">
                                    <p class="name-avatar col-lg-12 mb-4 style-link-none"><?= $post['author'] ?></p>
                                    <p class="time-posts col-lg-12 mb-4 style-link-none"><?= $date ?></p>
                                </div>
                            </div>
                            <div class="content-text-posts">
                                <h4 class="card-title link"><?= $post['title'] ?></h4>
                            </div>
                        </div>

                    </div>
                </div>
            </a>
        </div>
    </div>
</div>
<?php endif; ?>

<div class="errors-register">
    <?php if(!empty($error)): ?>
    <div class="alert alert-danger">
        <p>Vous n'avez pas rempli le formulaire correctement</p>
        <ul>
            <?php foreach($errors as $error): ?>
            <li><?= $error; ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
    <?php endif; ?>
</div>

<section class="section-contact" id="contact">
    <div class="container">
        <h2 class="text-center">Nous Contacter</h2>

        <form action="<?="index.php?action=sendToSupport" ?>" method="POST">
            <div class="row align-items-stretch mb-5">
                <div class="col-md-6">
                    <div class="form-group">
                        <input class="form-control" type="text" name="name" placeholder="Votre nom *" required="required" data-validation-required-message="Sil-Vous plaît entre votre prénom.">
                        <p class="help-block text-danger"></p>
                    </div>
                    <div class="form-group">
                        <input class="form-control" type="email" name="email" placeholder="Votre email *" required="required" data-validation-required-message="Sil-Vous plaît entre votre adresse email.">
                        <p class="help-block text-danger"></p>
                    </div>
                    <div class="form-group mb-md-0">
                        <input class="form-control" type="tel" name="phone" placeholder="Votre téléphone" data-validation-required-message="Sil-Vous plaît entre votre numéro.">
                        <p class="help-block text-danger"></p>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group form-group-textarea mb-md-0">
                        <textarea class="form-control" name="message" placeholder="Votre message *" required="required" data-validation-required-message="Sil-Vous plaît écrivez votre message."></textarea>
                        <p class="help-block text-danger"></p>
                    </div>
                </div>
            </div>
            <div class="text-center">
                <div id="success"></div>
                <button class="btn btn-primary btn-xl text-uppercase" type="submit">Envoyer le message</button>
            </div>
        </form>

    </div>
</section>

<footer class="footer py-2">
    <div class="container text-center">
        <div class="row align-items-center">
            <div class="col-lg-4 text-lg-left">Copyright © Your Website <span class="date-copyright"></span></div>
            <div class="col-lg-4 my-3">
                <a class="btn btn-dark btn-social mx-2 rounded-circle" href="https://www.facebook.com/patricia.mareau.37"><i class="fab fa-facebook-f"></i></a>
                <a class="btn btn-dark btn-social mx-2 rounded-circle" href="https://www.instagram.com/mareaupatricia/?hl=fr"><i class="fab fa-instagram"></i></a>
            </div>
            <div class="col-lg-4 text-lg-right">
                <a class="mr-3" href="#!">Politique de la vie privée</a>
                <a href="#!">Conditions d'utilisation</a>
            </div>
        </div>
    </div>
</footer>

<script src="src/Content/js/nav.js"></script>
