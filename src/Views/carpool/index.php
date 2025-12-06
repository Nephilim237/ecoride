<section class="er-fyw bg-chinese">
    <div class="container py-5">
        <div class="er-fyw-wrapper">
            <div class="row">
                <div class="col-md-12">
                    <h3 class="fs-24 text-center mb-4 outfit">
                        Quelle est votre prohaine destination ?
                    </h3>
                </div>
                <div class="col-12">
                    <div class="er-fyw-form-container d-flex align-items-center px-2">
                        <form action="<?= url('carpool/search') ?>" method="get" class="row w-100 outfit er-fyw-form">
                            <div class="col-md-10 col-sm-12 input-container bg-light rounded-start-pill rounded-end-0 py-1">
                                <div class="row">
                                    <div class="col-md-6 col-sm-12">
                                        <div class="row">
                                            <div class="col-md-6 col-sm-12 er-v-end-divider address-autocomplete">
                                                <input type="text" placeholder="Depart" name="lieu_depart"
                                                       value="<?= sanitize($_GET['lieu_depart'] ?? '') ?>"
                                                       autocomplete="off" id="lieu_depart"
                                                       class="form-control rounded-pill border-0 bg-transparent w-100 py-2 px-3">
                                                <div class="autocomplete-results" id="depart-results"></div>
                                            </div>
                                            <div class="col-md-6 col-sm-12 er-v-end-divider address-autocomplete">
                                                <input type="text" placeholder="Destination" name="lieu_arrivee"
                                                       value="<?= sanitize($_GET['lieu_arrivee'] ?? '') ?>"
                                                       autocomplete="off" id="lieu_arrivee"
                                                       class="form-control rounded-pill border-0 bg-transparent w-100 py-2 px-3">
                                                <div class="autocomplete-results" id="arrivee-results"></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-sm-12">
                                        <div class="row">
                                            <div class="col-md-7 col-sm-12 er-v-end-divider">
                                                <input type="date" placeholder="Date de depart" name="date_depart"
                                                       value="<?= sanitize($_GET['date_depart'] ?? '') ?>"
                                                       autocomplete="off" id="date-depart"
                                                       class="form-control rounded-pill border-0 bg-transparent w-100 py-2 px-3">
                                            </div>
                                            <div class="col-md-5 col-sm-12">
                                                <input type="number" placeholder="Nb.Passager" name="nb_passagers"
                                                       value="<?= sanitize($_GET['nb_passagers'] ?? '') ?>"
                                                       autocomplete="off"
                                                       class="form-control rounded-pill border-0 bg-transparent w-100 py-2 px-3">
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </div>
                            <input type="submit" value="Rechercher"
                                   class="btn btn-bg-green-2 col-md-2 col-sm-12 rounded-start-0 rounded-end-pill">
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="carpools-results py-5 my-5" id="carpools-results">
    <div class="container">
        <?php if ($searchParams): ?>
            <?php if (empty($carpools)): ?>
                <div class="empty-message">
                    <i class="fas fa-car-side fa-3x text-muted mb-3"></i>
                    <h3>Aucun covoiturage disponible</h3>
                    <!-- Affichage de la prochaine de disponible -->
                </div>
            <?php else: ?>
                <!-- Si le tableau des covoiturages n'est pas vide, on va afficher les covoiturages disponibles -->
                <h3 class="text-center fw-500 outfit mb-3"><?= count($carpools) ?> Covoiturages trouve(s)</h3>

                <div class="row">
                    <div class="col-md-3 col-sm-12 outfit">
                        <h6>Filtres</h6>
                    </div>
                    <div class="col-md-9 col-sm-12 outfit">
                        <?php foreach ($carpools as $carpool): ?>
                            <div class="card card-carpools outfit mb-3">
                                <div class="card-body">
                                    <div class="row align-items-center">
                                        <div class="col-md-8 col-sm-12">
                                            <div class="d-flex align-items-center mb-2">
                                                <div class="driver-avatar me-3">
                                                    <img src="<?= $carpool['conducteur']['photo'] ?? assets('img/avatar-default.png') ?>"
                                                         alt="" width="64px" class="rounded-circle">
                                                </div>
                                                <div class="driver-info">
                                                    <h5><?= $carpool['conducteur']['prenom'] . ' ' . $carpool['conducteur']['nom'] ?></h5>
                                                    <div class="text-warning">
                                                        <small>
                                                            <?= $carpool['conducteur']['note'] ? "{$carpool['conducteur']['note'] }/5" : 'Pas encore 
                                                            note'; ?>
                                                        </small>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="carpool-info">
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <div class="fs-14">
                                                        <strong><?= date('d/m/Y', strtotime($carpool['date_depart'])) ?></strong>
                                                        <span class="text-muted"> <?= date('H:i', strtotime
                                                            ($carpool['heure_depart'])) ?></span>
                                                    </div>
                                                    <div class="fs-14 text-end">
                                                        <strong><?= date('d/m/Y', strtotime($carpool['date_arrivee']))
                                                            ?></strong>
                                                        <span class="text-muted"> <?= date('H:i', strtotime
                                                            ($carpool['heure_arrivee'])) ?></span>
                                                    </div>
                                                </div>
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <div class="text-truncate">
                                                        <i class="fas fa-map-marker-alt er-text-main"></i>
                                                        <?= sanitize($carpool['lieu_depart']) ?>
                                                    </div>
                                                    <div class="mx-3">
                                                        <i class="fas fa-arrow-right text-muted"></i>
                                                    </div>
                                                    <div class="text-truncate text-end">
                                                        <i class="fas fa-flag-checkered text-info"></i>
                                                        <?= sanitize($carpool['lieu_arrivee']) ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4 col-sm-12 text-end">
                                            <div class="mb-2">
                                                <?php if ($carpool['is_ecologic']): ?>
                                                    <span class="badge bg-main-color px-3 rounded-pill">
                                                        <i class="fas fa-leaf"></i> Ecologique
                                                    </span>
                                                <?php endif; ?>

                                                <span class="badge bg-info px-3 rounded-pill">
                                                    <?= $carpool['places_restantes'] ?> Places restantes
                                                </span>
                                            </div>
                                            <div class="price h4 er-text-main mb-2">
                                                <?= $carpool['prix_personne'] ?> Credits
                                            </div>

                                            <div class="vehicle-info text-muted small mb-2">
                                                <?= $carpool['vehicule']['marque'] ?>
                                                <?= $carpool['vehicule']['modele'] ?>
                                            </div>

                                            <a href="#" class="btn btn-small btn-bg-main rounded-pill px-4">Voir les
                                                details
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endif; ?>
        <?php endif; ?>
    </div>
    <input type="hidden" name="" id="autocomplete-url" value="<?= url('carpool/autocomplete') ?>">
</section>

<script src="<?= assets('/js/autocomplete.js') ?>"></script>
