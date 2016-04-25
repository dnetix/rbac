<?php

return [
    'permissions' => [
        'rbac.manage.roles' => [
            'name' => 'Administrar roles de usuarios'
        ],
        
        'rbac.assign.permissions.to.role' => [
            'name' => 'Asignar permisos a roles'
        ],
        
        /*
        When a callback its provided to the permission it will be executed instead of the role checks
        The first argument always will be the authenticatable that its being checked and the others
        arguments are the ones provided when checking
        'post.edit' => [
            'name' => 'Modificar los posts que me pertenecen',
            'callback' => function($user, $post){
                return $user->id() == $post->userId();
            }
        ],
        */
        
        /*
        The callback could be also a string class path
        'post.edit' => [
            'name' => 'Modificar los posts que me pertenecen',
            'callback' => '\Namespace\Class@method'
        ],
        */
    ]    
];
