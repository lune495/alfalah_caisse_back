<?php
namespace App\GraphQL\Type;

use App\Models\Medecin;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Facades\GraphQL;
use Rebing\GraphQL\Support\Type as GraphQLType;

class MedecinType extends GraphQLType
{
    protected $attributes = [
        'name'          => 'Medecin',
        'description'   => ''
    ];

    public function fields(): array
    {
       return
            [
                'id'                        => ['type' => Type::id(), 'description' => ''],
                'nom'                       => ['type' => Type::string()],
                'prenom'                    => ['type' => Type::string()],
                'module'                    => ['type' => GraphQL::type('Module')],
            ];
    }

    // You can also resolve a field by declaring a method in the class
    // with the following format resolve[FIELD_NAME]Field()
    // protected function resolveEmailField($root, array $args)
    // {
    //     return strtolower($root->email);
    // }
}