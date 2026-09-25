<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;

class ArtisanRunnerController extends Controller
{
    /**
     * Check if request is authorized via active web session or secret key.
     */
    protected function isAuthorized(Request $request): bool
    {
        if (Auth::check()) {
            return true;
        }

        $secret = env('ARTISAN_KEY', 'assettracker-artisan-2026');
        $provided = $request->input('key', $request->query('key', ''));

        return !empty($provided) && hash_equals((string) $secret, (string) $provided);
    }

    /**
     * Display the web-based Artisan dashboard.
     */
    public function index(Request $request)
    {
        $defaultKey = env('ARTISAN_KEY', 'assettracker-artisan-2026');
        $key = $request->query('key', '');

        if (!$this->isAuthorized($request)) {
            return view('artisan.auth', [
                'providedKey' => $key,
            ]);
        }

        return view('artisan.index', [
            'key' => $key ?: $defaultKey,
            'output' => session('output'),
            'lastCommand' => session('lastCommand'),
            'status' => session('status'),
        ]);
    }

    /**
     * Execute an Artisan command and redirect or return output.
     */
    public function run(Request $request)
    {
        if (!$this->isAuthorized($request)) {
            return response("<h3 style='color:red;font-family:sans-serif;'>Unauthorized: Invalid or missing ?key= parameter.</h3><p>Use: <code>/artisan?key=" . env('ARTISAN_KEY', 'assettracker-artisan-2026') . "</code></p>", 403);
        }

        $command = trim($request->input('command', $request->query('command', 'migrate')));
        $key = $request->input('key', $request->query('key', env('ARTISAN_KEY', 'assettracker-artisan-2026')));

        $params = [];
        if ($command === 'migrate') {
            $params['--force'] = true;
        } elseif ($command === 'db:seed') {
            $params['--force'] = true;
        }

        try {
            $exitCode = Artisan::call($command, $params);
            $output = Artisan::output();

            if ($request->query('format') === 'raw' || !$request->has('from_ui')) {
                $statusColor = $exitCode === 0 ? '#10b981' : '#f87171';
                $statusText = $exitCode === 0 ? 'SUCCESS' : 'FAILED (Exit ' . $exitCode . ')';
                return response(
                    "<!DOCTYPE html><html><head><title>Artisan: {$command}</title><meta name='viewport' content='width=device-width, initial-scale=1'></head>" .
                    "<body style='background:#0f172a;color:#f8fafc;font-family:monospace;padding:24px;line-height:1.5;margin:0;'>" .
                    "<div style='max-width:800px;margin:0 auto;'>" .
                    "<div style='display:flex;justify-content:space-between;align-items:center;margin-bottom:16px;border-bottom:1px solid #334155;padding-bottom:12px;'>" .
                    "<div><h2 style='margin:0;color:#38bdf8;'>php artisan {$command}</h2><span style='color:{$statusColor};font-weight:bold;font-size:12px;'>{$statusText}</span></div>" .
                    "<a href='/artisan?key={$key}' style='color:#94a3b8;text-decoration:none;background:#1e293b;padding:8px 14px;border-radius:8px;font-size:13px;'>← Back to Runner</a>" .
                    "</div>" .
                    "<pre style='background:#020617;border:1px solid #1e293b;padding:16px;border-radius:10px;white-space:pre-wrap;word-break:break-all;color:#34d399;font-size:13px;'>" .
                    htmlspecialchars($output ?: '(Command executed with no standard output)') .
                    "</pre></div></body></html>"
                );
            }

            return redirect()->route('artisan.index', ['key' => $key])->with([
                'status' => $exitCode === 0 ? 'success' : 'error',
                'lastCommand' => $command,
                'output' => $output ?: 'Command completed successfully.',
            ]);
        } catch (\Throwable $e) {
            $msg = $e->getMessage();
            if ($request->query('format') === 'raw' || !$request->has('from_ui')) {
                return response(
                    "<!DOCTYPE html><html><head><title>Artisan Error</title><meta name='viewport' content='width=device-width, initial-scale=1'></head>" .
                    "<body style='background:#0f172a;color:#f8fafc;font-family:monospace;padding:24px;margin:0;'>" .
                    "<div style='max-width:800px;margin:0 auto;'>" .
                    "<h2 style='color:#ef4444;margin:0 0 12px 0;'>Error executing: php artisan {$command}</h2>" .
                    "<pre style='background:#450a0a;border:1px solid #7f1d1d;padding:16px;border-radius:10px;color:#fca5a5;white-space:pre-wrap;font-size:13px;'>" .
                    htmlspecialchars($msg) .
                    "</pre><br><a href='/artisan?key={$key}' style='color:#94a3b8;background:#1e293b;padding:8px 14px;border-radius:8px;text-decoration:none;'>← Back</a></div></body></html>",
                    500
                );
            }

            return redirect()->route('artisan.index', ['key' => $key])->with([
                'status' => 'error',
                'lastCommand' => $command,
                'output' => $msg,
            ]);
        }
    }

    /**
     * Direct shortcut to run migrate --force without any UI prompts.
     */
    public function migrate(Request $request)
    {
        $request->merge(['command' => 'migrate', 'format' => 'raw']);
        return $this->run($request);
    }
}
