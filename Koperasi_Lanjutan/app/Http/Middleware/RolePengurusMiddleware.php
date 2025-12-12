namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class RolePengurusMiddleware
{
    public function handle(Request $request, Closure $next, ...$roles)
    {
        // cek user auth pengurus
        if (!auth()->guard('pengurus')->check()) {
            abort(404);
        }

        // cek role
        if (!in_array(auth()->guard('pengurus')->user()->role, $roles)) {
            abort(404);
        }

        return $next($request);
    }
}
