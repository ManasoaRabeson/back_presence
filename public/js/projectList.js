function formatProjectListHTML(v, i) {
    var nomDossier = t('noFolder');
    // Ici tu construis ton HTML comme tu faisais avant (mode liste par exemple)

        // Déclaration de variable
        var p_ispaid = $(`.fact_${v.isPaid.idInvoiceStatus}`).empty();

        let menu_item = `
            <li class="menu-title">Action</li>
            <li><a href="/cfp/projets/${v.idProjet}/detail"><i class="fa-solid fa-eye"></i> ${t('apercu')}</a></li>
            `;

        let p_type_class = "";
        // Condition
        switch (v.project_type) {
            case 'Intra':
                p_type_class = `text-[#1565c0] bg-[#1565c0]/10`;
                break;
            case 'Inter':
                p_type_class = `text-[#7209b7] bg-[#7209b7]/10`;
                break;
            default:
                p_type_class =`text-slate-600 bg-slate-50`;
                break;
        };
        
        let p_modalite_class = "";
        let p_modalite_text = "";
        switch (v.modalite) {
            case 'Présentielle':
                p_modalite_class = `text-[#00b4d8] bg-[#00b4d8]/10`;
                p_modalite_text = t('presentielle');
                break;
            case 'En ligne':
                p_modalite_class = `text-[#fca311] bg-[#fca311]/10`;
                p_modalite_text = t('online');
                break;
            case 'Blended':
                p_modalite_class = `text-[#005f73] bg-[#005f73]/10`;
                p_modalite_text = t('blended');
                break;

            default:
                p_modalite_class = `text-[#00b4d8] bg-[#00b4d8]/10`;
                break;
        };

        let p_statut_class = "";
        let p_statut_text = "";
        switch (v.project_status) {
            case 'En préparation':
                p_statut_class = `text-white bg-[#F8E16F]`;
                p_statut_text = t('enpreparation');
                break;
            case 'Réservé':
                p_statut_class = `text-white bg-[#33303D]`;
                break;
            case 'En cours':
                p_statut_class = `text-white bg-[#369ACC]`;
                p_statut_text = t('encours');
                break;
            case 'Terminé':
                p_statut_class = `text-white bg-[#95CF92]`;
                p_statut_text = t('termine');
                break;
            case 'Annulé':
                p_statut_class = `text-white bg-[#DE324C]`;
                break;
            case 'Reporté':
                p_statut_class = `text-white bg-[#2E705A]`;
                break;
            case 'Planifié':
                p_statut_class = `text-white bg-[#CBABD1]`;
                p_statut_text = t('planifie');
                break;
            case 'Cloturé':
                p_statut_class = `text-white bg-[#6F1926]`;
                p_statut_text = t('cloture');
                break;

            default:
                p_statut_class = `text-slate-600 bg-slate-50`;
                break;
        };

        let p_paiement_modal_text = "";
        switch (v.paiement) {
            case "FMFP":
                p_paiement_modal_text = t('fmfp');
                break;
            case "Autres":
                p_paiement_modal_text = t('autres');
                break;
            case "Fonds Propres":
                p_paiement_modal_text = t('fondPropre');
                break;

            default:
                break;
        }

        switch (v.isPaid.idInvoiceStatus) {
            case 1: //brouillon
                p_ispaid.addClass(`text-slate-600 bg-slate-50`);
                p_ispaid.text(t('brouillon'));
                break;
            case 2: //Non envoyé
                p_ispaid.addClass(`text-rose-500 bg-rose-50`);
                p_ispaid.text(t('nonEnvoye'));
                break;
            case 3: //Envoyé
                p_ispaid.addClass(`text-[#37718e] bg-[#37718e]/10`);
                p_ispaid.text(t('envoye'));
                break;
            case 4: //payé
                p_ispaid.addClass(`text-teal-600 bg-teal-600/10`);
                p_ispaid.text(t('paye'));
                break;
            case 5: //partiel
                p_ispaid.addClass(`text-yellow-600 bg-yellow-600/10`);
                p_ispaid.text(t('partiel'));
                break;
            case 6: //Impayé
                p_ispaid.addClass(`text-red-400 bg-red-400/10`);
                p_ispaid.text(t('impaye'));
                break;
            case 7: //Convertis
                p_ispaid.addClass(`text-green-600 bg-green-600/10`);
                p_ispaid.text(t('convertis'));
                break;
            case 8: //Expiré
                p_ispaid.addClass(`text-red-600 bg-red-600/10`);
                p_ispaid.text(t('expire'));
                break;
            default:
                p_ispaid.addClass(`text-slate-600 bg-slate-50`);
                p_ispaid.text(t('nonFacture'));
                break;
        };

        let p_etp_clients = "";
        if (v.etp_name.length > 0) {
            if (v.idCfp_inter == null || v.idCfp_inter == "null") {
                $.each(v.etp_name, function (i_etp, v_etp) {
                    if (v_etp.etp_logo != null) {
                        p_etp_clients += `
                                    <img onclick="showCustomer(${v_etp.idEtp}, '/cfp/etp-drawer/', ${v.idProjet})" class="cursor-pointer inline-block h-[30px] w-[53.2px] grayscale hover:grayscale-0 duration-200 rounded-xl ring-2 ring-white" loading="lazy"
                                        src="${endpoint}/${bucket}/img/entreprises/${v_etp.etp_logo}"
                                        alt="" />
                                        `;
                    } else {
                        p_etp_clients += `
                                <div onclick="showCustomer(${v_etp.idEtp}, '/cfp/etp-drawer/', ${v.idProjet})" class="cursor-pointer inline-block h-[30px] w-[53.2px] rounded-xl ring-2 ring-white text-slate-600 bg-slate-100 flex font-bold items-center justify-center uppercase">${v_etp.etp_name[0]}</div>
                                `;
                    }
                });
            } else {
                $.each(v.etp_name, function (i_etp, v_etp) {
                    if (v_etp.etp_logo != null) {
                        p_etp_clients += `
                                        <img onclick="drawerClient(${v.idProjet}, ${v.idCfp_inter})" class="cursor-pointer inline-block h-[30px] w-[53.2px] grayscale hover:grayscale-0 duration-200 rounded-xl ring-2 ring-white" loading="lazy"
                                            src="${endpoint}/${bucket}/img/entreprises/${v_etp.etp_logo}"
                                            alt="" />
                                            `;
                    } else {
                        p_etp_clients +=`
                                    <div onclick="drawerClient(${v.idProjet}, ${v.idCfp_inter})" class="cursor-pointer inline-block h-[30px] w-[53.2px] rounded-xl ring-2 ring-white text-slate-600 bg-slate-100 flex font-bold items-center justify-center uppercase">${v_etp.etp_name[0]}</div>
                                    `;
                    }
                });
            }
        } else {
            p_etp_clients += `
            <div class="relative">
                    <span class="absolute -top-2 -right-2 rounded-full z-[99]"><i class="fa-solid text-amber-500 text-lg fa-fade fa-triangle-exclamation"></i></span>
                    <div onclick="drawerClient(${v.idProjet}, ${v.idCfp_inter})" data-bs-toggle="tooltip" title="${t('entrepriseTooltip')}" class="inline-block h-[30px] w-[53.2px] rounded-xl ring-2 ring-white text-slate-600 bg-slate-200 flex font-bold items-center justify-center uppercase"></div>
            </div>`;
        }

        let p_forms = "";
        if (v.formateurs.length > 0) {
            $.each(v.formateurs, function (i_f, v_f) {
                if (v_f.form_photo != null) {
                    p_forms += `
                                <img onclick="viewMiniCV(${v_f.idFormateur}, ${v.idProjet})" class="cursor-pointer inline-block h-8 w-8 rounded-full ring-2 ring-white" loading="lazy"
                                src="${endpoint}/${bucket}/img/formateurs/${v_f.form_photo}"
                                alt="" />
                                    `;
                } else {
                    p_forms += `
                            <div onclick="viewMiniCV(${v_f.idFormateur}, ${v.idProjet})" class="cursor-pointer inline-block h-8 w-8 rounded-full ring-2 ring-white text-slate-600 bg-slate-100 flex font-bold items-center justify-center uppercase">${v_f.form_initial_name[0]}</div>
                            `;
                }
            });
        } else {
            p_forms += `
            <div class="relative">
                <span class="absolute -top-2 -right-2 rounded-full z-[99]"><i class="fa-solid text-amber-500 text-lg fa-fade fa-triangle-exclamation"></i></span>
                <div class="inline-block h-8 w-8 rounded-full ring-2 ring-white text-slate-600 bg-slate-200 flex font-bold items-center justify-center uppercase"></div>
            </div>
                    `;
        }

        let p_apprs_list = "";
        if (v.apprCount > 0 && v.apprCount < 4) {
            $.each(v.apprs, function (i_ap, v_ap) {
                if (v_ap.emp_photo != null) {
                    p_apprs_list += `
                            <div class="avatar">
                                <div class="w-8 rounded-full overflow-hidden">
                                    <img src="${endpoint}/${bucket}/img/employes/${v_ap.emp_photo}" loading="lazy"/>
                                </div>
                            </div>`;
                } else {
                    p_apprs_list += `
                            <div class="avatar placeholder rounded-full overflow-hidden cursor-pointer">
                                <div class="bg-slate-200 text-slate-600 w-8 rounded-full">
                                    <span class="text-xl">${v_ap.emp_initial_name}</span>
                                </div>
                            </div>
                        `;
                }
            });
        } else if (v.apprCount >= 4) {
            const totalApprentices = v.apprs.length;
            const remainingApprentices = totalApprentices - 3;
            const baseNumber = Math.floor(totalApprentices / 10);

            for (let i = 0; i < 3; i++) {
                if (v.apprs[i].emp_photo != null) {
                    p_apprs_list += `
                            <div class="avatar">
                                <div class="w-8 rounded-full overflow-hidden">
                                    <img src="${endpoint}/${bucket}/img/employes/${v.apprs[i].emp_photo}" loading="lazy"/>
                                </div>
                            </div>`;
                } else {
                    p_apprs_list += `
                            <div class="avatar rounded-full overflow-hidden placeholder cursor-pointer">
                                <div class="bg-slate-200 text-slate-600 w-8 rounded-full">
                                    <span class="text-xl">${v.apprs[i].emp_initial_name}</span>
                                </div>
                            </div>
                        `;
                }
            }

            p_apprs_list += `
                    <div class="avatar placeholder cursor-pointer rounded-full overflow-hidden">
                        <div class="bg-neutral !opacity-100 text-white w-8 rounded-full">
                            <span class="text-md">+${remainingApprentices}</span>
                        </div>        
                    </div>
                `;
        } else {
            p_apprs_list += `
            <div class="relative">
                <span class="absolute top-0 right-0 rounded-full z-[99]"><i class="fa-solid text-amber-500 text-lg fa-fade fa-triangle-exclamation"></i></span>
                <span id="empty_appr" class="avatar-group -space-x-4 relative rtl:space-x-reverse">
                    <div class="avatar">
                        <div class="inline-block h-8 w-8 rounded-full ring-2 ring-white text-slate-600 bg-slate-200 flex font-bold items-center justify-center uppercase"></div>
                    </div>
                    <div class="avatar">
                        <div class="inline-block h-8 w-8 rounded-full ring-2 ring-white text-slate-600 bg-slate-200 flex font-bold items-center justify-center uppercase"></div>
                    </div>
                    <div class="avatar">
                        <div class="inline-block h-8 w-8 rounded-full ring-2 ring-white text-slate-600 bg-slate-200 flex font-bold items-center justify-center uppercase"></div>
                    </div>
                </span>
            </div>`;
        }

        function generateStars(score) {
            let p_star = "";
            score = parseFloat(score);
        
            for (let i = 1; i <= 5; i++) {
                if (score >= i) {
                    // étoile pleine
                    p_star += `<i class="fa-solid fa-star text-yellow-500"></i>`;
                } else if (score >= i - 0.5) {
                    // demi étoile
                    p_star += `<i class="fa-solid fa-star-half-stroke text-yellow-500"></i>`;
                } else {
                    // étoile vide
                    p_star += `<i class="fa-solid fa-star text-slate-300"></i>`;
                }
            }
        
            return p_star;
        }

        let note = v.general_note ? v.general_note[0] : 0;
        let p_star = generateStars(note);

        p_note = "";
        p_note +=`<div class="inline-flex items-center justify-end gap-1">
            <div id="raty_notation_${v.idProjet}"
                data-val="${v.general_note ? v.general_note[0] : '0'}"
                class="inline-flex items-center gap-1 raty_notation_id"> ${p_star}
            </div>
            <p class="font-medium text-gray-500 p_note_${v.idProjet}">
            ${v.general_note ? formatNumber(v.general_note[0], 1, ',', ' ') : '0'}</p>
                <span class="text-gray-400">
                    (${v.general_note ? v.general_note[1] : '0'} ${t('avis')})
                </span>
            </div>`;

        //  Activer les tooltip bootstrap
        loadBsTooltip();
        const sub_contractor = $('.sub_contractor_' + v.idProjet);
        sub_contractor.empty();
        if (v.sub_name != null) {
            if (v.idUser == v.idSubContractor) {
                sub_contractor.append(`<h3 class="text-md text-slate-400 text-wrap line-clamp-2">Commanditaire : ${v.cfp_name}</h3>`);
            } else {
                sub_contractor.append(`<h3 class="text-md text-slate-400 text-wrap line-clamp-2">Sous-traitant : ${v.sub_name}</h3>`);
            }
        } else {
            sub_contractor.html('');
        }  

        let html = `
            <tr class="hover:bg-slate-100 duration-300">
                <td>${i+1}</td>
                <td onclick="${v.idModule ? `showFormation(${v.idModule})` : ''}">${v.module_name}</td>
                <td>${formatDate(v.dateDebut)} - ${formatDate(v.dateFin)}</td>
                <td><div class="flex -space-x-2 text-slate-400">${p_etp_clients}</div></td>
                <td><span class="px-3 py-1 text-base rounded-xl ${p_statut_class}">${p_statut_text}</span></td>
                <td><span class="px-3 py-1 text-base rounded-xl ${p_type_class}">${v.project_type}</span></td>
                <td><span class="px-3 py-1 text-base rounded-xl ${p_modalite_class}">${p_modalite_text}</span></td>
                <td><div onclick="showApprenants('/cfp/apprenant-drawer/${v.idProjet}', ${v.idProjet})" class="avatar-group -space-x-4 relative rtl:space-x-reverse" data-bs-toggle="tooltip"
                title="${t('students')}">${p_apprs_list}</div></td>
                <td><div class="flex -space-x-2 opacity-60 text-slate-400">${p_forms}</div></td>
                <td><button class="btn btn-sm btn-outline btn-primary" onclick="drawerPresence(${v.idProjet}, ${v.idCfp_inter})">Présence</span></td>
            </tr>
        `;

    // let html1 = `
    //     <div class="grid col-span-1 p-4 h-[380px] rounded-2xl border-[1px] border-slate-200 shadow-md hover:shadow-xl duration-300 bg-white group">
    //         <div class="grid grid-cols-6">
    //             <div class="grid col-span-5 grid-cols-subgrid">
    //                 <span class="inline-flex items-center gap-6 justify-between mb-1 sub_contractor_${v.idProjet}">
    //                 </span>
    //                 <h3 onclick="${v.idModule ? `showFormation(${v.idModule})` : ''}" class="cursor-pointer text-xl text-slate-600 font-medium w-full line-clamp-2">${v.module_name}</h3>
    //                 <span class="inline-flex items-center h-full py-2 gap-2 p_ref_${v.idProjet}">
    //                     <p class="text-slate-600 italic">Ref : ${v.project_reference}</p>
    //                 </span>
    //                 <span class="inline-flex items-center h-full py-2 gap-2">${p_note}</span>
    //             </div>

    //             <div class="grid col-span-1 justify-end">
    //                 <div class="dropdown dropdown-end">
    //                     <div name="menu" tabindex="0" role="button" class="btn bg-white m-1 h-12 w-12 flex items-center rounded-xl duration-200 cursor-pointer justify-center hover:bg-slate-100">
    //                         <i class="fa-solid fa-ellipsis-vertical text-slate-400 text-xl"></i>
    //                     </div>
    //                     <ul tabindex="0" class="dropdown-content project_menu_${v.idProjet} menu bg-base-100 rounded-box z-[1] w-72 p-2 shadow text-slate-600">${menu_item}</ul>
    //                 </div>
    //             </div>
    //         </div>
    //         <div class="inline-flex items-center gap-2 py-2 w-full justify-between">
    //             <div class="inline-flex items-center gap-2">
    //                 <span class="px-3 py-1 text-base rounded-xl ${p_type_class}">${v.project_type}</span>
    //                 <span class="px-3 py-1 text-base rounded-xl ${p_modalite_class}">${p_modalite_text}</span>
    //                 <span class="px-3 py-1 text-base rounded-xl text-slate-600 bg-slate-50">${p_paiement_modal_text}</span>
    //             </div>
    //             <span class="px-3 py-1 text-base rounded-xl ${p_statut_class}">${p_statut_text}</span>
    //         </div>

    //         <div class="grid col-span-1 py-1">
    //             <div data-bs-toggle="tooltip" onclick="showLieuDeReperage('/cfp/planreperage-drawer/${v.idProjet}')" title="${t('lieuDeFormation')}" class="inline-flex items-center gap-2 w-full">
    //                 <i class="fa-solid fa-location-dot text-sm text-slate-400"></i>
    //                 <p class="text-base text-slate-500 cursor-pointer line-clamp-1 hover:underline underline-offset-4">${v.salle_name}, ${v.li_name ?? '--'}, ${v.salle_quartier ?? '--'}, ${v.ville} (${v.salle_code_postal})</p>
    //             </div>
    //         </div>

    //         <div class="grid col-span-1 py-1">
    //             <div data-bs-toggle="tooltip" onclick="showDossiers('/cfp/dossier-drawer/${v.idProjet}')" title="${t('folder')}" class="inline-flex items-center gap-2 w-max">
    //                 <i class="fa-solid fa-folder text-sm text-slate-400"></i>
    //                 <p class="text-base text-slate-500 cursor-pointer hover:underline underline-offset-4">${nomDossier}</p>
    //             </div>
    //         </div>

    //         <div class="py-2 w-full text-slate-500 line-clamp-2">${v.project_description ?? t('noDescription')}
    //         </div>

    //         <div class="inline-flex items-center gap-2 py-3 w-full justify-between">
    //             <div onclick="showApprenants('/cfp/apprenant-drawer/${v.idProjet}', ${v.idProjet})" class="avatar-group -space-x-4 relative rtl:space-x-reverse" data-bs-toggle="tooltip"
    //                 title="${t('students')}">${p_apprs_list}</div>

    //             <div class="flex -space-x-2 text-slate-400">${p_etp_clients}</div>
    //         </div>

    //         <div class="inline-flex items-center gap-2 py-2 w-full justify-between">
    //             <div class="flex -space-x-2 opacity-60 text-slate-400">${p_forms}</div>

    //             <div class="inline-flex items-center gap-4">
    //                 <span class="inline-flex relative items-center gap-2 cursor-pointer" onclick="showSessions('/cfp/session-drawer/${v.idProjet}', ${v.idProjet})" data-bs-toggle="tooltip" title="${t('hrs')}">
    //                     <p class="text-lg text-slate-600 font-medium">${v.totalSessionHour} <span
    //                             class="text-slate-400 underline font-normal">${t('hours')}</span>
    //                     </p>
    //                 </span>
    //                 <span class="inline-flex items-center gap-2 cursor-pointer relative" onclick="showSessions('/cfp/session-drawer/${v.idProjet}', ${v.idProjet})">
    //                     ${v.seanceCount <= 0 ? `<i class="fa-solid fa-triangle-exclamation -top-1 -right-1 text-amber-500 fa-fade absolute"></i>` : ''}
    //                     <p class="text-lg text-slate-600 font-medium" data-bs-toggle="tooltip" title="Sessions"><span id="session_${v.idProjet}">${v.seanceCount}</span> <span
    //                             class="text-slate-400 underline font-normal">${t('sessions')}</span></p>
    //                 </span>
    //                 <span onclick="showDocuments('/cfp/document-drawer/${v.idProjet}')" class="inline-flex items-center gap-2 cursor-pointer">
    //                     <p class="text-lg text-slate-600 font-medium" data-bs-toggle="tooltip" title="Documents">${v.nbDocument ?? 0} <span
    //                             class="text-slate-400 underline font-normal">${t('documents')}</span></p>
    //                 </span>
    //             </div>
    //         </div>

    //         <div class="py-2 grid grid-cols-4 divide-x divide-slate-200">
    //             <div class="grid col-span-2 grid-cols-2">
    //                 <div class="flex flex-col items-start ml-3">
    //                     <h5 class="text-slate-400 text-base capitalize">${t('deb')} :</h5>
    //                     <p class="text-slate-600 text-lg md:text-base lg:text-xl font-semibold">${formatDate(v.dateDebut)}</p>
    //                 </div>
    //                 <div class="flex flex-col items-start ml-3">
    //                     <h5 class="text-slate-400 text-base capitalize">${t('fin')} :</h5>
    //                     <p class="text-slate-600 text-lg md:text-base lg:text-xl font-semibold">${formatDate(v.dateFin)}</p>
    //                 </div>
    //             </div>
    //             <div class="grid col-span-1">
    //                 <div class="flex flex-col items-start ml-3">
    //                     <h5 class="text-slate-400 text-base">${t('price')} :</h5>
    //                     <p class="text-slate-600 text-lg md:text-base lg:text-xl font-bold">${v.total_ht ?? 0}</p>
    //                 </div>
    //             </div>
    //             <div class="grid col-span-1">
    //                 <div class="flex flex-col items-start ml-3">
    //                     <h5 class="text-slate-400 text-base">Paiement :</h5>
    //                     <span class="px-3 py-1 text-base rounded-xl fact_${v.isPaid.idInvoiceStatus}"></span>
    //                 </div>
    //             </div>
    //         </div>
    //     </div>
    //     `;

    return html;
}


function _showProjet(res) {

    var projetCount = $('.projetCount');
    projetCount.html('');

    projetCount.append(`${res.projetCount ?? '--'}`);

    var headDate = $('#scrollArea');
    headDate.html('');

    if (res.projectDates.length <= 0) {
        headDate.append(`<div class="h-screen w-screen flex justify-center text-2xl font-semibold text-slate-600">${t('noProject')}</div>`);
    } else {
        $.each(res.projectDates, function (i, head) {
            headDate.append(`
            <div class="menu w-full p-0 [&_li>*]:rounded-none">
                <li class="menu-title !text-2xl p-3 bg-slate-50 rounded-xl text-slate-700 capitalize">${head.headDate}</li>
                <table class="table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th class="w-[25%]">Formation</th>
                            <th class="w-[15%]">Date</th>
                            <th class="w-[8%]">Entreprise</th>
                            <th>Statut</th>
                            <th>Type de projet</th>
                            <th>Modalité</th>
                            <th>Apprenants</th>
                            <th>Formateurs</th>
                        </tr>
                    </thead>
                    <tbody id="contentArea_${i}" data-view="carte" data-val="${head.headDate}">
                    </tbody>
                </table>
            </div>`);

            headDate.ready(function () {

                // Initialise Clusterize
                let clusterize = new Clusterize({
                    scrollId: 'scrollArea',
                    contentId: `contentArea_${i}`,
                    rows: []
                });

                var content_grid_project = headDate.find(`.content[data-val="${head.headDate}"]`);
                content_grid_project.html('');

                var content_list_project = headDate.find(`.content[data-val="list_${head.headDate}"]`);
                content_list_project.html('');

                // Ajouter un écouteur d'événement pour chaque changement d'état de la checkbox
                $('input[name="view_check"]').on('change', toggleView);

                // Initialiser l'état de la section en fonction de l'état de la checkbox
                toggleView(); // Appel initial pour définir l'état des sections au chargement de la page
  
                let rows = [];

                $.each(res.projets, function (i, v) {

                    if (v.headDate == head.headDate) {
                        rows.push(formatProjectListHTML(v, i));
                    }
                });

                // Injection dans Clusterize
                clusterize.update(rows);
            });
        });
    }
}


function formatNumber(number, decimals, dec_point, thousands_sep) {
    // Limiter à 'decimals' chiffres après la virgule
    let n = number.toFixed(decimals);

    // Remplacer le point par la virgule pour la partie décimale
    n = n.replace('.', dec_point);

    // Séparer les parties entière et décimale
    const parts = n.split(dec_point);
    let integerPart = parts[0];
    const decimalPart = parts.length > 1 ? dec_point + parts[1] : '';

    // Ajouter les séparateurs de milliers
    integerPart = integerPart.replace(/\B(?=(\d{3})+(?!\d))/g, thousands_sep);

    return integerPart + decimalPart;
}


// Fonction pour formater la date
function formatDate(dateString) {
    // Convertir la chaîne de date en un objet Date
    var date = moment(dateString.replace(/-/g, '/'), 'YYYY-MM-DD');

    // Formater la date selon le format souhaité
    var formattedDate = date.format('DD MMM YYYY');

    return formattedDate;
}

function formatAmount(nombre) {
    // const nombre = 3100000;
    const formattedNumber = nombre.toLocaleString('en-US', { minimumFractionDigits: 1, maximumFractionDigits: 1 });
    console.log(formattedNumber); // Affichera "3.1M"
    return formattedNumber;
}