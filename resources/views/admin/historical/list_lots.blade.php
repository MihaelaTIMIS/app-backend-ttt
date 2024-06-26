
@extends('admin.template')

@section('page_level_css')
    <link rel="stylesheet" href="{{asset('css/datable.css')}}"/>
@endsection

@section('content')
    <div class="pt-2">
        <div class="bg-white p-2">
            <h3 class="text-bold text-center">
               Tous les lots @if( count ($histories) > 0) ( {{ count ($histories) }} ) @endif
            </h3>
        </div>
    </div>
    <div class="bg-white p-3 mt-3">
        {{-- debut tableau --}}

            <div class="container">
                @if(!empty(Session::get('errorNotification')))
                <div class="alert alert-warning alert-dismissible fade show" role="alert">
                    {!! \Session::get('errorNotification') !!}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                @endif
                <form action="{{ route('filter_historique') }}" method="POST">
                    @csrf
                    <div class="row">
                        <div class="col-sm">
                            <label for="">Date de debut</label>
                          <input  placeholder= "Date de debut" type="date" class="form-control" name="date_start" value="" >

                        </div>
                        <div class="col-sm">
                          <label for="">Date de fin</label>
                          <input  placeholder= "Date de fin" type="date" class="form-control" name="date_end" value="">
                        </div>
                      </div>
                      <br>

                      <div class="row">
                        <div class="col-sm-6">
                            <button type="submit" class="btn bg-admin-base ">
                                <i class="fas fa-search"></i> Filter le resultat
                            </button>

                        </div>
                     </div>
                </form>
         </div>

        <div class="d-flex justify-content-end">
            <button class="100"
                    data-href="ticket_csv" id="export" class="btn btn-success btn-sm" onclick="exportTasks(event.target);"
                    class="btn mt-3 btn-admin-success" type="button" style="width: 228px">
                Exporter en csv
            </button>
        </div>

          <br><br>
        <table  class="display table table-bordered mt-5" id="listetypehabitats">
            <thead>
            <tr>
                <th class="itteration-width">N° du ticket</th>
                <th class="image-width">Lot gagné</th>
                <th class="image-width">Nom du gagnant</th>
                <th class="image-width">Date de jeu </th>
                <th class="image-width">Lot déja récupéré <b>(oui ou non)</b></th>
                <th class="image-width">Action</th>
            </tr>
            </thead>
            <tbody>
            @foreach ($histories as $histories)
                <tr>

                    <td class="image-width">{{ $histories->getTicket->number }}</td>
                    <td class="image-width">{{ $histories->getTicket->getLot->libelle }}</td>
                    <td class="image-width">{{ $histories->getUser->name }}</td>
                    <td class="image-width">
                        {{ \Carbon\Carbon::parse($histories->startDate)->format('d/m/Y')}}
                    </td>
                    <td class="image-width">@if (!empty($histories->takenAt)) oui @else non @endif</td>
                    @if(Auth::user()->role =="administrator" || Auth::user()->role =="employee")
                         <td class="image-width">
                        <form action="{{ route('update_historical')}}" method="POST" >
                            @csrf
                            <input type="hidden" value="{{ $histories->id }}" name="historical_id">

                            <button type="submit" @if(!empty($histories->takenAt)) disabled @endif class="btn btn-sm bg-admin-base ">
                                <i class="fas fa-check"></i> recupérer
                            </button>
                        </form>
                        <a class="btn btn-success" href="{{route('moreinfo',$histories->id)}}"> Voir plus d'information</a>
                    </td>
                    @endif

                </tr>
            @endforeach
            </tbody>
        </table>
        {{-- fin tableau --}}


    </div>
@endsection

@section('optional_js')
    <script src="{{ asset('js/bootstrap-confirmation.min.js') }}"></script>
    <script src="{{ asset('js/datable.js') }}"></script>
    <script src="{{ asset('js/lang-all.js') }}"></script>
    <script>

        function exportTasks(_this) {
            let _url = $(_this).data('href');
            window.location.href = _url;
            console.log(_url, "information")
        }

        $('document').ready(function(){
            $('[data-toggle="tooltip"]').tooltip();
            $('[data-toggle=confirmation]').confirmation({ rootSelector: '[data-toggle=confirmation]' });
        });

        //jquery datatables
        $('#listetypehabitats').DataTable({
            ordering: false,
            language: {
                processing:     "Traitement en cours...",
                search:         "Rechercher&nbsp;:",
                lengthMenu:    "Afficher _MENU_ &eacute;l&eacute;ments",
                info:           "Affichage de l'&eacute;lement _START_ &agrave; _END_ sur _TOTAL_ &eacute;l&eacute;ments",
                infoEmpty:      "Affichage de l'&eacute;lement 0 &agrave; 0 sur 0 &eacute;l&eacute;ments",
                infoFiltered:   "(filtr&eacute; de _MAX_ &eacute;l&eacute;ments au total)",
                infoPostFix:    "",
                loadingRecords: "Chargement en cours...",
                zeroRecords:    "Aucun &eacute;l&eacute;ment &agrave; afficher",
                emptyTable:     "Aucune donnée disponible dans le tableau",
                paginate: {
                    first:      "Premier",
                    previous:   "Pr&eacute;c&eacute;dent",
                    next:       "Suivant",
                    last:       "Dernier"
                },
                 aria: {
                    sortAscending:  ": activer pour trier la colonne par ordre croissant",
                    sortDescending: ": activer pour trier la colonne par ordre décroissant"
                }
            }
        });

    </script>
@endsection
