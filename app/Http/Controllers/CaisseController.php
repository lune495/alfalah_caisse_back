<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Intervention\Image\Facades\Image as Image;
use App\Models\{Service,Outil,User,Produit,ElementService,Log,TypeService,ClotureCaisse,Depense,Vente};
use \PDF;
use App\Events\MyEvent;

class CaisseController extends Controller
{

    private $queryName = "services";

    public function save(Request $request)
    {
        try 
        {
            $errors =null;
            $item = new Service();
            $log = new Log();
            $user = Auth::user();
            if (!empty($request->id))
            {
                $item = Service::find($request->id);
            }
            if (empty($request->medecin_id))
            {
                $errors = "Renseignez le Medecin";
            }
            if (empty($request->nom_complet))
            {
                $errors = "Renseignez le nom";
            }
            $str_json_type_service = json_encode($request->type_services);
            $type_service_tabs = json_decode($str_json_type_service, true);

            // Ajoutez un verrouillage de la table factice pour éviter les opérations concurrentes.
            DB::table('service_locks')->lockForUpdate()->get();
            DB::beginTransaction();
            $item->nom_complet = $request->nom_complet;
            $item->nature = $request->nature;
            $item->montant = $request->montant;
            $item->adresse = $request->adresse;
            $item->remise = $request->remise;
            $item->medecin_id = $request->medecin_id;
            $item->module_id = $request->module_id;
            $item->user_id = $user->id;
            $montant = 0;
            if (!isset($errors)) 
            {
                $item->save();
                $id = $item->id;
                if($item->save())
                {
                    foreach ($type_service_tabs as $type_service_tab) 
                    {
                        $tpc = TypeService::find($type_service_tab['type_service_id']);
                        if (!isset($tpc)) {
                        $errors = "Type  Service inexistant";
                        }
                        $element_service = new ElementService();
                        $element_service->service_id =  $id;
                        $element_service->type_service_id =  $type_service_tab['type_service_id'];
                        $element_service->save();
                        if($element_service->save())
                        {
                            $montant  = $montant + $element_service->type_service->prix;
                        }
                    }
                    $log->designation = $item->module->nom;
                    $log->id_evnt = $id;
                    $log->date = $item->created_at;
                    $log->prix = $montant;
                    $log->remise = $item->remise;
                    $log->montant = $item->montant;
                    $log->user_id = $user->id;
                    $log->save();
                }
                DB::commit();
                return  Outil::redirectgraphql($this->queryName, "id:{$id}", Outil::$queries[$this->queryName]);
            }
            if (isset($errors))
            {
                throw new \Exception('{"data": null, "errors": "'. $errors .'" }');
            }
        } catch (\Throwable $e) {
                DB::rollback();
                return $e->getMessage();
        }
    }
    public function closeCaisse(Request $request)
    {
        try {
            // Calculez le montant total de la caisse à la fermeture (par exemple, en ajoutant les montants des consultations non facturées)
            // $totalCaisse = $request->montant_total;
            $errors =null;
            $montant = 0;
            $allDatesNotNull = DB::table('cloture_caisses')->whereNull('date_fermeture')->doesntExist();
            $count = DB::table('cloture_caisses')->count();
            if ($count === 0) {
                $logs = DB::table('logs')
                    ->select('designation', DB::raw('SUM(prix) AS total_prix'))
                    // ->where(function ($query) {
                    //     $query->where('created_at', '>', function ($subQuery) {
                    //         $subQuery->select('date_fermeture')
                    //             ->from('cloture_caisses')
                    //             ->whereNotNull('date_fermeture')
                    //             ->orderByDesc('date_fermeture')
                    //             ->limit(1);
                    //     });
                    // })
                    ->where('created_at', '<=', now())
                    ->groupBy('designation')
                    ->orderBy('designation')
                    ->get();
            } else {
                $logs = DB::table('logs')
                    ->select('designation', DB::raw('SUM(prix) AS total_prix'))
                    ->where(function ($query) {
                        $query->where('created_at', '>=', function ($subQuery) {
                            $subQuery->select('date_fermeture')
                                ->from('cloture_caisses')
                                ->orderByDesc('date_fermeture')
                                ->limit(1);
                        });
                    })
                    ->where('created_at', '<=', now())
                    ->groupBy('designation')
                    ->orderBy('designation')
                    ->get();
            }        
            foreach ($logs as $log){

                $montant = $montant + $log->total_prix;
            }
            if ($montant == 0)
            {
                $errors = "Vous pouvez pas cloturer une caisse Vide";
            }
            $user = Auth::user();   
            // Enregistrez les détails de la clôture de caisse
            if (isset($errors))
            {
                throw new \Exception('{"data": null, "errors": "'. $errors .'" }');
            }
            $caisseCloture = new ClotureCaisse();
            $caisseCloture->date_fermeture = now(); // Ou utilisez la date/heure appropriée
            $caisseCloture->montant_total = $montant;
            $caisseCloture->user_id = $user->id;
            $caisseCloture->save();

            return response()->json(['message' => 'Caisse fermée avec succès.']);
        } catch (\Throwable $e) {
            return $e->getMessage();
            // return response()->json(['error' => 'Une erreur est survenue lors de la clôture de la caisse.']);
        }
    }
    
    public function Notif()
    {
        // event(new MyEvent("Hello"));
        dd("test");
    }

    public function generatePDF($id)
    {
        $service = Service::find($id);
        if($service!=null)
        {
         $data = Outil::getOneItemWithGraphQl($this->queryName, $id, true);
        //dd($data);
         $pdf = PDF::loadView("pdf.ticket-service", $data);
        $measure = array(0,0,225.772,650.197);
        return $pdf->setPaper($measure, 'orientation')->stream();
            //  return $pdf->stream();
        }else{
         $data = Outil::getOneItemWithGraphQl($this->queryName, $id, false);
            return view('notfound');
        }
    }
    public function generatePDF3($id)
    {
        $queryName = "ventes";
        $vente = Vente::find($id);
        if($vente!=null)
        {
        $data = Outil::getOneItemWithGraphQl($queryName, $id, true);
        $results['vente'] = $vente;
        $pdf = PDF::loadView("pdf.ticket-pharmacie", $data);
        $measure = array(0,0,225.772,650.197);
        return $pdf->setPaper($measure, 'orientation')->stream();
            //  return $pdf->stream();
        }else{
            return view('notfound');
        }
    }

    public function statutPDFpharmacie($id)
    {
        $vente = Vente::find($id);
        if($vente!=null)
        {
            if($vente->paye != 1){
                $ventes = $vente->vente_produits()->get();
                foreach ($ventes as $key => $vt) {
                    $produit = Produit::find($vt->produit_id);
                    $produit->qte = isset($produit) ? $produit->qte - $vt->qte : $produit->qte;
                    $produit->save();
                }
                $vente->paye = 1;
                $vente->save();
                event(new MyEvent($vente));
            }
            $log = Log::where('id_evnt',$id)->first();
            if($log!=null)
            {
                $log->statut_pharma = false;
                $log->save();
            }
        }   
    }

        public function generatePDF2()
    {
            // Calculez le montant total de la caisse à la fermeture (par exemple, en ajoutant les montants des consultations non facturées)
            // $totalCaisse = $request->montant_total;
            $errors =null;
            $montant = 0;
            $results = [];
            $count = DB::table('cloture_caisses')->count();
            if ($count === 0) {
                $data = DB::table('logs')
                    ->select('designation',DB::raw('SUM(prix) AS total_prix'))
                    ->where('created_at','>',"1900-09-08 19:16:39")
                    ->where('created_at','<=',now())
                    ->where('statut_pharma','=','false')
                    ->groupBy('designation')
                    ->orderBy('designation')
                    ->get()
                    ->toArray();
                    $latestClosureDate = now()->format('Y-m-d H:i:s');
                    // Depense
                    $depenses = DB::table('depenses')
                    ->orderBy('id', 'asc')
                    ->where('created_at','>',"1900-09-08 19:16:39")
                    ->where('created_at','<=',now())
                    ->get();
                    $results['data'] = $data;
                    $results['depenses'] = $depenses;
                    $results['derniere_date_fermeture'] = $latestClosureDate;
                    $results['current_date'] = now()->format('Y-m-d H:i:s');
                    // dd($results);
            } else {
                $data = DB::table('logs')
                    ->select('designation', DB::raw('SUM(prix) AS total_prix'))
                    ->where(function ($query) {
                        $query->where('created_at', '>=', function ($subQuery) {
                            $subQuery->select('date_fermeture')
                                ->from('cloture_caisses')
                                ->orderByDesc('date_fermeture')
                                ->limit(1);
                        });
                    })
                    ->where('created_at', '<=', now())
                    // ->where('statut_pharma','=',false)
                    ->groupBy('designation')
                    ->orderBy('designation')
                    ->get();

                    $latestClosureDate = DB::table('cloture_caisses')
                    ->select(DB::raw('MAX(date_fermeture) AS latest_date_fermeture'))
                    ->whereNotNull('date_fermeture')
                    ->first();
                    //dd($latestClosureDate);
                    // Depense
                    $depenses = DB::table('depenses')
                    ->orderBy('id', 'asc')
                    ->whereBetween('created_at', [$latestClosureDate ? $latestClosureDate->latest_date_fermeture : "0000-00-00 00:00:00", now()])
                    ->get();
                    $results['data'] = $data;
                    $results['depenses'] = $depenses;
                    $results['derniere_date_fermeture'] = $latestClosureDate->latest_date_fermeture;
                    $results['current_date'] = now()->format('Y-m-d H:i:s');
                    //dd($results);
            }
        $pdf = PDF::loadView("pdf.situation-pdf",$results);
        return $pdf->stream();
    }
    public function SituationParFiltreDate($start)
    {
        $data = DB::table('cloture_caisses')
            ->select('*')
            ->where('created_at','>',"{$start} 00:00:00")
            ->where('created_at','<=',"{$start} 23:59:59")
            ->get();
        dd($data);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

     /**
     * Search for a name.
     * @param str $name
     */
    public function search($name)
    {
        //
        return Consultation::where('titre','like','%'.$name)->get();
    }
}