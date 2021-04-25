<?php

namespace App\Transformers;

use App\Models\User;
use League\Fractal\TransformerAbstract;

class UserTransformer extends TransformerAbstract
{
    /**
     * List of resources to automatically include
     *
     * @var array
     */
    protected $defaultIncludes = [
        //
    ];

    /**
     * List of resources possible to include
     *
     * @var array
     */
    protected $availableIncludes = [
        //
    ];

    /**
     * A Fractal transformer.
     *
     * @return array
     */
    public function transform(User $user)
    {
        return [
            'identifier' => (int) $user->id,
            'name' => (string) $user->name,
            'email' => (string) $user->email,
            'isVerified' => (int) $user->verified,
            'isAdmin' => ($user->admin === 'true'),
            'createdDate' => (string) $user->created_at,
            'changedDate' => (string) $user->updated_at,
            'deletedDate' => isset($user->deleted_at) ? (string) $user->deleted_at : null,
            'links' => [

                [
                    'rel' => 'self',
                    'href' => route('users.show', $user->id)
                ],


            ]

        ];
    }

    public static function  originalAttributes($index)
    {
        $collection = [
            'identifier' => 'id',
            'name' => 'name',
            'email' => 'email',
            'isAdmin' => 'admin',
            'isVerified' => 'verified',
            'createdDate' => 'created_at',
            'changedDate' => 'updated_at',
            'deletedDate' => 'deleted_at',
        ];
        return isset($collection[$index]) ? $collection[$index] : null;
    }

    public static function  transformedAttributes($index)
    {
        $collection = [
            'id' => 'identifier',
            'name' => 'name',
            'email' => 'email',
            'admin' => 'isAdmin',
            'verified' => 'isVerified',
            'created_at' => 'createdDate',
            'updated_at' => 'changedDate',
            'deleted_at' => 'deletedDate',
        ];
        return isset($collection[$index]) ? $collection[$index] : null;
    }
}
