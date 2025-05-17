<?php 
namespace App\Http\Middleware;

use Closure;
use Illuminate\Container\Attributes\Log;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserGroup
{
public function handle(Request $request, Closure $next, string ...$groups): Response
{
    $user = $request->user();

    // $groups is now a variadic parameter array, like ['a', 's']
    if (!$user || !in_array($user->user_group, $groups)) {
        abort(403, 'Unauthorized');
    }

    return $next($request);
}
}
