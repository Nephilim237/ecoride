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
            <div class="row">
                <div class="col-md-8 col-sm-12">
                    <div class="card mb-4">
                        <div class="card-header bg-green er-text-light">
                            <h5 class="mb-0">
                                <i class="fas fa-route me-2"></i> Details du trajet
                                <?php if($carpool['general']['ecologique']): ?>
                                    <span class="badge bg-chinese rounded-pill py-2 fs-10">
                                        <span class="fas fa-leaf"></span> Ecologique
                                    </span>
                                <?php endif; ?>
                            </h5>
                        </div>
                    </div>
                </div>

                <div class="col-md-4 col-sm-12">
                    <div class="card mb-4">
                        <div class="card-header bg-green er-text-light">
                            <h5 class="mb-0">
                                <i class="fas fa-info-circle me-2"></i>
                                Informations Pratiques
                            </h5>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>
</section>


<script src="<?= assets('js/confirm-booking.js') ?>"></script>
