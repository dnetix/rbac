<?php

return [

    /**
     * Allows to register a callback to log the results of the rbac operations
     * it will return $user, $ability, $result, $arguments
     */
    'log_callback' => false,
    
    'permissions' => [
        'rbac.manage.roles' => [
            'name' => 'Administrar roles de usuarios'
        ],
        
        'rbac.assign.permissions.to.role' => [
            'name' => 'Asignar permisos a roles'
        ],

        /*
         * You can provide some date range that a permission needs to fulfill in order to be
         * granted. This date range should be provided in text with the notation used in the class
         * \Dnetix\Dates\DateRangeChecker
        'rbac.assign.permissions.to.role' => [
            'name' => 'Asignar permisos a roles',
            'date_range' => 'LV8-16|S8-12'
        ],
        */

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
