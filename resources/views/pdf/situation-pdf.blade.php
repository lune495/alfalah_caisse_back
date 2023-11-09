@extends('pdf.layouts.layout-export2')
@section('title', "Situation Generale")
@section('content')

<h4 class="situation-heading">Situation Generale du {{$derniere_date_fermeture}} au {{$current_date}}</h4>
<div class="table-container">
    <!-- Tableau de gauche (RECETTE) -->
    <div class="table-wrapper left">
        <h4>RECETTE</h4>
        <table class="custom-table">
            <!-- En-tête -->
            <tr>
                <th>DESIGNATION</th>
                <th>MONTANT</th>
            </tr>
            <!-- Contenu -->
            <!-- ... Votre boucle foreach existante ... -->
            {{$montant_total = 0}}
            @foreach($data as $sum)
                {{$montant_total = $montant_total + $sum->total_prix }}
                <tr>
                    <td><center> {{ \App\Models\Outil::toUpperCase($sum->designation)}}</center></td>
                    <td>{{\App\Models\Outil::formatPrixToMonetaire($sum->total_prix, false, false)}}</td>
                </tr>
            @endforeach
            <tr>
                <td colspan="2">
                    <div>
                        <p class="badge" style="line-height:15px;">Total</p>
                        <p style="line-height:5px;text-align:center">{{ \App\Models\Outil::formatPrixToMonetaire($montant_total, false, false)}}</p>
                    </div>
                </td>
            </tr>
        </table>
    </div>

    <!-- Tableau de droite (DEPENSE) -->
    <div class="table-wrapper right">
        <h4>DEPENSE</h4>
        <table class="custom-table">
            <!-- En-tête -->
            <tr>
                <th>Nature</th>
                <th>MONTANT</th>
            </tr>
            <!-- Contenu -->
            <!-- ... Votre boucle foreach existante pour les dépenses ... -->
            {{$montant_total_depense = 0}}
            @foreach($depenses as $dep)
                {{$montant_total_depense = $montant_total_depense + $dep->montant }}
                <tr>
                    <td><center> {{ \App\Models\Outil::premereLettreMajuscule($dep->nom)}}</center></td>
                    <td> <center>{{$dep->montant}}</center></td>
                </tr>
            @endforeach
            <tr>
                <td colspan="2">
                    <div>
                        <p class="badge" style="line-height:15px;">Total</p>
                        <p style="line-height:5px;text-align:center">{{ \App\Models\Outil::formatPrixToMonetaire($montant_total_depense, false, false)}}</p>
                    </div>
                </td>
            </tr>
            <!-- Ajoutez la ligne colorée si nécessaire -->
                <tr class="colorful-row">
                    <td colspan="2" style="padding-top: 10px; font-size: 15px">
                        <p>Solde Caisse :</p>
                        <p style="font-weight: bold; font-size: 20px">{{ \App\Models\Outil::formatPrixToMonetaire($montant_total - $montant_total_depense, false, true)}}</p>
                    </td>
                </tr>
        </table>
    </div>
</div>

<!-- ... Le reste de votre modèle ... -->

@endsection
