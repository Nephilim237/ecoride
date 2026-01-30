<section class="flash-messages">
    <div class="container">
        <?php if ($this->session->has_flash('error')): ?>
            <div class="alert alert-warning">
                <?= $this->session->get_flash('error') ?>
            </div>
        <?php endif; ?>
    </div>
</section>

<section class="my-md-5">
    <div class="container outfit">
        <div class="justify-content-center align-items-center mb-4">
            <?php if ($canParticipate && !empty($carpool)): ?>
                <form action="<?= url('carpool/apply') ?>" method="post" id="confirm-form">
                    <input type="hidden" name="nb_passagers" value="<?= $canUserParticipate['seats'] ?>">
                    <input type="hidden" name="covoiturage" value="<?= $carpool['id'] ?>">

                    <div class="checkbox-container mb-3 bg-green-90 p-3">
                        <input type="checkbox" class="form-check-input" id="confirm-box" name="confirm">
                        <label for="confirm-box" class="form-check-label">
                            <strong>Je confirme ma participation</strong><br>
                            <small class="text-muted">
                                Je comprends que <strong><?= $canUserParticipate['totalCoast'] ?> credits</strong>
                                seront debités de mon solde
                                <br>
                                Je serai remboursé uniquement si le conducteur annule le trajet
                            </small>
                        </label>

                        <div class="invalid-feedback">
                            Vous devez confirmer votre participation
                        </div>
                    </div>

                    <div class="d-grid gap-2 d-md-flex justify-content-md-between">
                        <a href="<?= $_SERVER['HTTP_REFERER'] ?? url('carpool') ?>"
                           class="btn btn-bg-chinese btn-sm rounded-pill px-3 outfit fs-16">
                            <i class="fas fa-arrow-left pe-2"></i> Retour aux recherches
                        </a>

                        <button type="submit" class="btn btn-bg-green-2 btn-sm rounded-pill px-3 outfit fs-16">
                            Participer pour <?= $canUserParticipate['totalCoast'] ?> credits
                        </button>
                    </div>
                </form>
            <?php endif; ?>
        </div>

        <?php dump($carpool, $canUserParticipate); ?>

        <!--        Afficher les info du trajet-->
        <?php if (!empty($carpool)): ?>
            <div class="row mb-3">
                <div class="col-md-8 col-sm-12">
                    <div class="card">
                        <div class="card-header bg-green er-text-light">
                            <h6 class="mb-0">
                                <i class="fas fa-road me-2"></i>Détails du trajet
                                <?php if ($carpool['general']['ecologique']): ?>
                                    <span class="badge bg-chinese rounded-pill ms-2 px-3">
                                        <span class="fas fa-leaf"></span> Ecologique
                                    </span>
                                <?php endif; ?>
                            </h6>
                        </div>
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="departure">
                                    <div class="d-flex gap-2 align-items-center">
                                        <div class="badge bg-main-color rounded-pill mb-2">
                                            <i class="fas fa-map-marker-alt"></i>
                                        </div>
                                        <h6>Départ</h6>
                                    </div>

                                    <div class="timeline-content">
                                        <p class="mb-1"><strong><?= $carpool['depart']['lieu'] ?></strong></p>
                                        <p class="text-muted fs-14">
                                            <i class="fas fa-calendar-alt me-1"></i> <?= $carpool['depart']['date_formatee'] ?>
                                            <i class="fas fa-clock me-1"></i> <?= $carpool['depart']['heure_formatee'] ?>
                                        </p>
                                    </div>
                                </div>
                                <div class="timeline-connection">
                                    <div class="badge bg-main-color rounded-pill">
                                        <i class="fas fa-clock me-2"></i>
                                        <?= $carpool['general']['duree']['affichage'] ?>
                                    </div>
                                </div>
                                <div class="arrival">
                                    <div class="d-flex gap-2 align-items-center">
                                        <div class="badge bg-main-color rounded-pill mb-2">
                                            <i class="fas fa-flag-checkered"></i>
                                        </div>
                                        <h6>Arrivée</h6>
                                    </div>

                                    <div class="timeline-content">
                                        <p class="mb-1"><strong><?= $carpool['arrivee']['lieu'] ?></strong></p>
                                        <p class="text-muted fs-14">
                                            <i class="fas fa-calendar-alt me-1"></i> <?= $carpool['arrivee']['date_formatee'] ?>
                                            <i class="fas fa-clock me-1"></i> <?= $carpool['arrivee']['heure_formatee'] ?>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 col-sm-12">
                    <div class="card">
                        <div class="card-header bg-green er-text-light">
                            <h6 class="mb-0">
                                <i class="fas fa-info-circle me-2"></i>Détails du trajet
                            </h6>
                        </div>
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <span>Prix/Personne : </span>
                                <span><?= $carpool['general']['tarif'] ?> credits</span>
                            </div>

                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <span>Plces disponibles : </span>
                                <span><?= $carpool['general']['places_restantes'] ?></span>
                            </div>
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <span>Durée du trajet : </span>
                                <span><?= $carpool['general']['duree']['affichage'] ?></span>
                            </div>
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <span>Statut : </span>

                                <?php if ($carpool['general']['statut'] === 'prevu'): ?>
                                    <span class="badge bg-main-color rounded-pill">Programmé</span>
                                <?php elseif ($carpool['general']['statut'] === 'en cours'): ?>
                                    <span class="badge bg-info rounded-pill">En cours</span>
                                <?php elseif ($carpool['general']['statut'] === 'termine'): ?>
                                    <span class="badge bg-danger rounded-pill">Terminé</span>
                                <?php else : ?>
                                    <span class="badge bg-green rounded-pill">
                                        <?= $carpool['general']['statut'] ?>
                                    </span>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-md-4 col-sm-12">
                    <div class="card">
                        <div class="card-header bg-green er-text-light">
                            <h6 class="mb-0">
                                <i class="fas fa-user-cog me-2"></i> Chauffeur
                            </h6>
                        </div>

                        <div class="card-body">
                            <div class="text-center">
                                <img src="<?= $carpool['conducteur']['photo'] ?? assets('img/avatar-default.png') ?>"
                                     alt="Photo de profil de <?= sanitize($carpool['conducteur']['nom'] . ' ' . $carpool['conducteur']['prenom'] ??
                                         $carpool['conducteur']['pseudo']) ?> "
                                     class="rounded-circle img-fluid img-thumbnail mb-3">
                                <h5>
                                    <?= sanitize($carpool['conducteur']['nom'] . ' ' . $carpool['conducteur']['prenom'] ??
                                        $carpool['conducteur']['pseudo']) ?>
                                </h5>

                                <div class="rating mb-2">
                                    <?= display_rate_stars($carpool['conducteur']['note']) ?> <br>
                                    <span class="text-muted ms-2 fs-6">
                                        <?= $carpool['conducteur']['note'] . "/5" ?> sur <?= $carpool['conducteur']['nb_avis'] . " avis" ?>
                                    </span>
                                </div>

                                <p class="text-muted">
                                    Membre depuis
                                    <strong>
                                        <?= $carpool['conducteur']['membre_depuis'] ?>
                                    </strong>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 col-sm-12">
                    <div class="card">
                        <div class="card-header bg-green er-text-light">
                            <h6 class="mb-0">
                                <i class="fas fa-car me-2"></i> Véhicule
                            </h6>
                        </div>

                        <div class="card-body">
                            <div class="text-center">
                                <h4>
                                    <?= sanitize($carpool['vehicule']['marque']) ?>
                                    <?= sanitize($carpool['vehicule']['modele']) ?>
                                </h4>

                                <div class="row mb-3">
                                    <div class="col-6">
                                        <div class="info-item">
                                            <small class="text-muted">Immatriculation </small>
                                            <div class="fw-bold"><?= sanitize($carpool['vehicule']['immatriculation']) ?></div>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="info-item">
                                            <small class="text-muted">Mise en circulation en </small>
                                            <div class="fw-bold"><?= sanitize($carpool['vehicule']['annee_circulation']) ?></div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-6">
                                        <div class="info-item">
                                            <small class="text-muted">Couleur </small>
                                            <div class="fw-bold"><?= sanitize($carpool['vehicule']['couleur']) ?></div>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="info-item">
                                            <small class="text-muted">Energie</small>
                                            <div class="fw-bold">
                                                <?= !empty($carpool['vehicule']['energie']) ? sanitize($carpool['vehicule']['energie']) : 'Classique' ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-6">
                                        <div class="info-item">
                                            <small class="text-muted">Place(s) </small>
                                            <div class="fw-bold"><?= sanitize($carpool['vehicule']['nb_places']) ?></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 col-sm-12">
                    <div class="card">
                        <div class="card-header bg-green er-text-light">
                            <h6 class="mb-0">
                                <i class="fas fa-users me-2"></i> Passagers inscrits
                            </h6>
                        </div>

                        <div class="card-body">
                            <?php if (!empty($carpool['passagers'])): ?>
                                <ul class="list-group">
                                    <?php foreach ($carpool['passagers'] as $passager): ?>
                                        <li class="list-group-item d-flex align-items-center">
                                            <img src="<?= $passager['photo'] ?? assets('img/avatar-default.png') ?>"
                                                 width="40" height="40" class="rounded-circle me-3" alt="">

                                            <div>
                                                <strong>
                                                    <?= $passager['nom'] . ' ' . $passager['prenom'] ?? $passager['pseudo'] ?>
                                                </strong>
                                                <div class="text-muted small">
                                                    Inscrit le <?= $passager['date_formatee'] ?>
                                                    . <?= $passager['nb_places'] ?> places
                                                </div>
                                            </div>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                            <?php else: ?>
                                <div class="text-center text-muted py-3">
                                    <i class="fas fa-user-friends fa-2x mb-3"></i>
                                    <p>Aucun passager pour le moment.</p>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

            </div>
            <div class="card">
                <div class="card-header bg-green er-text-light">
                    <h6 class="mb-0">
                        <i class="fas fa-comments me-2"></i> Avis sur le chauffeur
                    </h6>
                </div>

                <div class="card-body">
                    <?php if (!empty($carpool['avis'])): ?>
                        <div class="row">
                            <?php foreach ($carpool['avis'] as $avis): ?>
                                <div class="card h-100 mb-3">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-start gap-3 align-items-start mb-2">
                                            <div class="d-flex align-items-center">
                                                <img src="<?= sanitize($avis['passager']['photo'] ?? assets('img/avatar-default.png')) ?>"
                                                     class="rounded-circle me-2" width="32" height="32" alt="">
                                                <strong>
                                                    <?= sanitize($avis['passager']['nom'] . ' ' . $avis['passager']['prenom'] ??
                                                        $avis['passager']['pseudo']) ?>
                                                </strong>
                                            </div>

                                            <div class="text-warning">
                                                <?php for ($i = 1; $i <= 5; $i++): ?>
                                                    <i class="fa<?= $i <= $avis['note'] ? 's' : 'r' ?> fa-star"></i>
                                                <?php endfor; ?>
                                            </div>
                                        </div>
                                        <p class="mb-2">
                                            <?= nl2br(sanitize($avis['commentaire'])) ?>
                                        </p>
                                        <small class="text-muted">
                                            <i class="fas fa-clock me-1"></i> <?= $avis['date_formatee'] ?>
                                        </small>

                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php else : ?>
                        <div class="text-center text-muted py-3">
                            <i class="fas fa-comment-slash fa-2x mb-3"></i>
                            <p>Ce chauffeur n'a pas encore été noté.</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        <?php endif; ?>
    </div>
</section>


<script src="<?= assets('js/confirm-booking.js') ?>"></script>
