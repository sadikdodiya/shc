<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
        'App\Models\Brand' => 'App\Policies\BrandPolicy',
        'App\Models\Product' => 'App\Policies\ProductPolicy',
        'App\Models\FaultType' => 'App\Policies\FaultTypePolicy',
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();

        // Custom user provider for email/phone authentication
        Auth::provider('eloquent', function ($app, array $config) {
            return new class($app['hash'], $config['model']) extends \Illuminate\Auth\EloquentUserProvider {
                public function retrieveByCredentials(array $credentials)
                {
                    if (empty($credentials) || 
                        (count($credentials) === 1 && 
                         array_key_exists('password', $credentials))) {
                        return;
                    }

                    // First we will add each credential element to the query as a where clause.
                    // Then we can execute the query and, if we found a user, return it in a
                    // Eloquent User "model" that will be utilized by the Guard instances.
                    $query = $this->newModelQuery();

                    foreach ($credentials as $key => $value) {
                        if (str_contains($key, 'password')) {
                            continue;
                        }

                        if (is_array($value) || $value instanceof \ArrayAccess) {
                            $query->whereIn($key, $value);
                        } elseif ($key === 'email') {
                            // Check if the input is an email or phone
                            if (filter_var($value, FILTER_VALIDATE_EMAIL)) {
                                $query->where('email', $value);
                            } else {
                                // If not a valid email, try to find by phone
                                $query->where('phone', $value);
                            }
                        } else {
                            $query->where($key, $value);
                        }
                    }

                    return $query->first();
                }
            };
        });
    }
}
