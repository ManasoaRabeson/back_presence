@extends('layouts.masterEmp')

@section('content')
    <div class="flex flex-col w-full h-full overflow-y-scroll">
        <div class="w-full h-full p-2 mx-auto lg:container">
            <div class="flex flex-col" id="headDate">
            </div>
        </div>
    </div>
@endsection

@push('custom_style')
    <style>
        .applyBtn {
            background: #5B5966;
            border: none;
        }

        .applyBtn:hover {
            background: #4b4a53;
            border: none;
        }
    </style>
@endpush

@section('script')
    <script>
        function lineProgress() {
            var lineProgress = '';

            lineProgress =
                `
            <div id="progress-container" class="w-full h-1 bg-gray-200 rounded-full">
                <div id="progress-bar" class="h-1 bg-[#a462a4] rounded-full"></div>
            </div>
            `;

            return lineProgress;
        }
    </script>
    <script src="{{ asset('js/filter/filter_projets_emps.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/daterangepicker.min.js') }}"></script>
    <script src="{{ asset('js/filter/newFilter.js') }}"></script>
    <script src="{{ asset('js/moment.min.js') }}"></script>
    <script src="{{ asset('js/moment.fr.min.js') }}"></script>
    <script src="{{ asset('js/projectList/projectListEmp.js') }}"></script>
    <script>
        $(document).ready(function() {
            _getProjet(function(data) {
                _showProjet(data);
            });
            getDropdownItem();
        });

        var endpoint = "{{ $endpoint }}";
        var bucket = "{{ $bucket }}";

        var digitalOcean = endpoint + '/' + bucket;

        function _getProjet(callback) {
            $.ajax({
                type: "get",
                url: "/projetsEmp/list",
                dataType: "json",
                beforeSend: function() {
                    var content_grid_project = $('#headDate');
                    content_grid_project.html('');
                    content_grid_project.append(lineProgress());
                    const $progressBar = $('#progress-bar');
                    let progress = 0;
                    const interval = setInterval(() => {
                        if (progress >= 98) {
                            clearInterval(interval);
                        } else {
                            progress += 1;
                            $progressBar.css('width', `${progress}%`);
                        }
                    }, 8); //8ms
                },
                success: function(res) {
                    // console.log(res);

                    callback(res);
                }
            });
        }

        function manageProject(type, route) {
            $.ajax({
                type: type,
                url: route,
                data: {
                    _token: '{!! csrf_token() !!}',
                    nbPlace: $("#get_place").val()
                },
                dataType: "json",
                success: function(res) {
                    console.log('Manage project-->', res)
                    if (res.success) {
                        toastr.success(res.success, 'Opération effectuée avec succès', {
                            timeOut: 1500
                        });
                        location.reload();
                    } else {
                        toastr.error(res.error, 'Erreur', {
                            timeOut: 1500
                        });
                    }
                },
                error: function(error) {
                    console.log(error);
                }
            });
        };

        function repportProject(idProjet) {
            let dateDebut = $('.dateDebutProjetDetail_' + idProjet).val();
            let dateFin = $('.dateFinProjetDetail_' + idProjet).val();

            console.log(dateDebut, dateFin);

            $.ajax({
                type: "patch",
                url: "/projetsEmp/" + idProjet + "/repport",
                data: {
                    _token: '{!! csrf_token() !!}',
                    dateDebut: dateDebut,
                    dateFin: dateFin
                },
                dataType: "json",
                success: function(res) {
                    if (res.success) {
                        toastr.success(res.success, 'Opération effectuée avec succès', {
                            timeOut: 1500
                        });
                        location.reload();
                    } else {
                        console.log(res.error);
                        toastr.error(res.error, 'Erreur', {
                            timeOut: 1500
                        });
                    }
                }
            });
        };
    </script>
@endsection
