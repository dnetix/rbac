# Role Based Access Control

## Installation

With composer
```
composer require dnetix/rbac
```

Add the service provided to the app.php config file

```
\Dnetix\Rbac\RbacServiceProvider::class,
```

Publish the configurations and migrations for this package
```
php artisan vendor:publish
```
Modify the rbac.php config file with your own permissions

Make sure that the Models or Classes that will be using permissions and roles implements from the interface
```
Illuminate\Contracts\Auth\Authenticatable;
```

### TODO

* Revoke permissions to specific authenticatables (Users)
* Cache the results provided for the repository, they change rarely