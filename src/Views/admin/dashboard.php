<section class="dashboard-page bg-green-90" id="dashboard-page">
    <div class="container outfit py-4">
        <!-- Topbar -->
        <div class="topbar bg-green d-flex justify-content-between align-items-center mb-4">
            <div class="brand d-flex align-items-center fw-600">
                <span class="dot"></span>
                <span>Tableau de Bord</span>
            </div>
            <form class="er-form d-none d-md-flex align-items-center gap-2">
                <input class="form-control form-control-sm rounded-pill px-3 border-0" placeholder="Rechercher un trajet">
                <select class="form-select form-select-sm rounded-pill px-3" style="min-width:160px">
                    <option value="">Choisir Statut</option>
                    <option>en Attente</option>
                    <option>En Cours</option>
                    <option>Terminé</option>
                    <option>Annulé</option>
                </select>
                <button class="btn btn-sm btn-bg-chinese-2" title="Recherche avec filtres">
                    <i class="fa fa-filter"></i>
                </button>
            </form>
            <div class="actions d-flex align-items-center gap-2">
                <span class="badge bg-light px-3 py-2 er-text-dark rounded-pill">
                    <i class="fa fa-clock-o"></i> Dernière mise à jour: 12 Nov 2025, 14:10
                </span>
            </div>
        </div>


        <!-- Metrics -->
        <div class="row g-3 mb-3">
            <div class="col-6 col-lg-3">
                <div class="card-plain bg-green-80 p-3">
                    <div class="metric"><span class="value">57</span><span class="sub">Covoiturages</span></div>
                    <div class="progress-soft mt-3">
                        <div class="bar" style="width:42%"></div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-lg-3">
                <div class="card-plain bg-green-80 p-3">
                    <div class="metric"><span class="value">32</span><span class="sub">Réservations</span></div>
                    <div class="progress-soft mt-3">
                        <div class="bar" style="width:55%"></div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-lg-3">
                <div class="card-plain bg-green-80 p-3">
                    <div class="metric"><span class="value">88</span><span class="sub">Passagers</span></div>
                    <div class="progress-soft mt-3">
                        <div class="bar" style="width:70%"></div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-lg-3">
                <div class="card-plain bg-green-80 p-3">
                    <div class="metric"><span class="value">10</span><span class="sub">Chauffeurs</span></div>
                    <div class="progress-soft mt-3">
                        <div class="bar" style="width:20%"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Table -->
        <div class="mt-4">
            <div class="d-flex justify-content-between align-items-center border-bottom">
                <ul class="nav er-tab nav-tabs" id="carpoolTab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="recent-carpools-tab"
                                data-bs-toggle="tab" data-bs-target="#recentCarpools"
                                type="button">
                            <i class="fas fa-road"></i> Covoiturages récents
                        </button>
                    </li>
                </ul>
                <div class="d-flex gap-2 align-items-center">
                    <span class="chip"><i class="far fa-calendar-alt"></i> Période: Nov 1–12</span>
                </div>
            </div>
        </div>
        <div class="table-wrap mb-3" id="recentCarpools" role="tabpanel">
            <table class="table carpool-table align-middle mb-0">
                <thead>
                <tr>
                    <th style="min-width:100px" class="bg-green-80">ID #</th>
                    <th style="min-width:180px" class="bg-green-80">Trajet</th>
                    <th style="min-width:120px" class="bg-green-80">Date Heure</th>
                    <th style="min-width:140px" class="bg-green-80">Conducteur</th>
                    <th style="min-width:160px" class="bg-green-80">Statut</th>
                    <th style="min-width:260px" class="bg-green-80">Note Chauffeur</th>
                    <th style="width:70px"class="bg-green-80">Actions</th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td>#RF-1042</td>
                    <td class="truncate">Anita Sharma</td>
                    <td>₹1,250</td>
                    <td><span class="badge rounded-pill px-3 er-bg-primary"><i class="fas fa-running"></i> Processing</span>
                    </td>
                    <td><span class="chip"><i class="far fa-calendar-alt"></i> 10 Nov 2025</span></td>
                    <td>
                        <div class="note">
                            <div class="label">Note</div>
                            UTR pending confirmation from bank.
                        </div>
                    </td>
                    <td class="text-end">
                        <button class="btn btn-sm btn-bg-chinese"><i class="fa fa-eye"></i></button>
                    </td>
                </tr>
                <tr>
                    <td>#RF-1038</td>
                    <td class="truncate">Rahul Jain</td>
                    <td>₹3,980</td>
                    <td><span class="badge rounded-pill px-3 er-bg-success"><i class="fas fa-check-circle"></i> Approved</span>
                    </td>
                    <td><span class="chip"><i class="far fa-calendar-alt"></i> 08 Nov 2025</span></td>
                    <td>
                        <div class="note">
                            <div class="label">Note</div>
                            Approved by auditor.
                        </div>
                    </td>
                    <td class="text-end">
                        <button class="btn btn-sm btn-bg-chinese"><i class="fa fa-eye"></i></button>
                    </td>
                </tr>
                <tr>
                    <td>#RF-1035</td>
                    <td class="truncate">Suresh Kumar</td>
                    <td>₹870</td>
                    <td><span class="badge rounded-pill px-3 er-bg-danger"><i class="fas fa-times-circle"></i> Rejected</span>
                    </td>
                    <td><span class="chip"><i class="far fa-calendar-alt"></i> 07 Nov 2025</span></td>
                    <td>
                        <div class="note">
                            <div class="label">Note</div>
                            Bank details mismatch.
                        </div>
                    </td>
                    <td class="text-end">
                        <button class="btn btn-sm btn-bg-chinese"><i class="fa fa-eye"></i></button>
                    </td>
                </tr>
                <tr>
                    <td>#RF-1031</td>
                    <td class="truncate">Meena Iyer</td>
                    <td>₹2,540</td>
                    <td><span class="badge rounded-pill px-3 er-bg-warning"><i class="fas fa-hourglass-half"></i> Pending</span>
                    </td>
                    <td><span class="chip"><i class="far fa-calendar-alt"></i> 06 Nov 2025</span></td>
                    <td>
                        <div class="note">
                            <div class="label">Note</div>
                            Awaiting customer document.
                        </div>
                    </td>
                    <td class="text-end">
                        <button class="btn btn-sm btn-bg-chinese"><i class="fa fa-eye"></i></button>
                    </td>
                </tr>
                <tr>
                    <td>#RF-1027</td>
                    <td class="truncate">Vikas Pawar</td>
                    <td>₹1,120</td>
                    <td><span class="badge rounded-pill px-3 er-bg-purple"><i class="fa fa-undo"></i> Refunded</span></td>
                    <td><span class="chip"><i class="far fa-calendar-alt"></i> 04 Nov 2025</span></td>
                    <td>
                        <div class="note">
                            <div class="label">Note</div>
                            UTR: 2055XXXX909
                        </div>
                    </td>
                    <td class="text-end">
                        <button class="btn btn-sm btn-bg-chinese"><i class="fa fa-eye"></i></button>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>

        <!-- Single request timeline -->
        <div class="row g-3 mt-3">
            <div class="col-lg-6">
                <div class="card-plain bg-green-80 p-3">
                    <h6 class="mb-3">Avis récents</h6>
                    <ol class="list-group list-group-numbered">
                        <li class="list-group-item d-flex justify-content-between align-items-start">
                            <div class="ms-2 me-auto">
                                <div class="fw-bold">Request submitted</div>
                                10 Nov 2025, 09:42 AM
                            </div>
                            <span class="badge bg-success rounded-pill px-3">Done</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-start">
                            <div class="ms-2 me-auto">
                                <div class="fw-bold">Verification in progress</div>
                                11 Nov 2025, 03:10 PM
                            </div>
                            <span class="badge bg-primary rounded-pill px-3">Active</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-start">
                            <div class="ms-2 me-auto">
                                <div class="fw-bold">Refund issued</div>
                                —
                            </div>
                            <span class="badge bg-secondary rounded-pill px-3">Next</span>
                        </li>
                    </ol>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="card-plain bg-green-80 p-3">
                    <h6 class="mb-3">Actions rapides</h6>
                    <div class="d-flex flex-wrap gap-2">
                        <button class="btn btn-sm btn-outline-success rounded-pill px-3">
                            <i class="fa fa-check"></i> Approve
                        </button>
                        <button class="btn btn-sm btn-outline-danger rounded-pill px-3">
                            <i class="fa fa-times"></i> Reject
                        </button>
                        <button class="btn btn-sm btn-bg-main rounded-pill px-3">
                            <i class="fa fa-undo"></i> Envoyer un Message
                        </button>
                        <button class="btn btn-sm btn-bg-chinese-2 rounded-pill px-3">
                            <i class="fa fa-download"></i> Exportation Excel
                        </button>
                    </div>
                </div>
            </div>
        </div>

    </div>

</section>