<?php

namespace App\GraphQL\Query;

use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Query;
use Rebing\GraphQL\Support\Facades\GraphQL;
use App\Models\Service;
use App\Models\ClotureCaisse;
use Illuminate\Support\Facades\Auth;

class ServiceQuery extends Query
{
    protected $attributes = [
        'name' => 'services'
    ];

    public function type(): Type
    {
        return Type::listOf(GraphQL::type('Service'));
    }

    public function args(): array
    {
        return
        [
            'id'                 => ['type' => Type::int()],
            'nom_complet'        => ['type' => Type::string()],
        ];
    }

    public function resolve($root, $args)
    {
        $query = Service::query();
        $user = Auth::user();
        if (isset($args['id']))
        {
            $query = $query->where('id', $args['id']);
        }
        // if($user->email != "alassane@gmail.com")
        // {
            // Obtenez la date de fermeture la plus récente depuis la table ClotureCaisse
        $latestClosureDate = ClotureCaisse::orderBy('date_fermeture', 'desc')
        ->value('date_fermeture');
        if(isset($latestClosureDate))
        {
            $query = $query->whereBetween('created_at', [$latestClosureDate, now()]);
        }   
        // }
        $query->orderBy('id', 'desc');
        $query = $query->get();
        return $query->map(function (Service $item)
        {
            return
            [
                'id'                      => $item->id,
                'nom_complet'             => $item->nom_complet,
                'nature'                  => $item->nature,
                'montant'                 => $item->montant,
                'adresse'                 => $item->adresse,
                'remise'                  => $item->remise,
                'medecin'                 => $item->medecin,
                'module'                  => $item->module,
                'montant_total'           => $item->montant_total,
                'medecins'                => $item->medecins,
                'user'                    => $item->user,
                'created_at'              => $item->created_at,
                'element_services'        => $item->element_services,
            ];
        });

    }
}
