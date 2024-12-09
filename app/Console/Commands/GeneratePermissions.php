<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use ReflectionClass;

class GeneratePermissions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */

    protected $signature = 'permissions:generate {--guard_name=web}';
    protected $description = 'Generate permissions based on controllers and their methods';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Get the guard_name option or use 'web' as default
        $guardName = $this->option('guard_name') ?? 'web';

        // Truncate the permissions table to remove existing entries
        DB::table('permissions')->truncate();

        // Define the namespace for controllers
        $controllerNamespace = 'App\\Http\\Controllers\\';

        // Path to controllers directory
        $controllerPath = app_path('Http/Controllers');

        // Get all controller files
        $controllerFiles = File::allFiles($controllerPath);

        foreach ($controllerFiles as $file) {
            // Get the full class name with namespace
            $controllerClass = $controllerNamespace . $file->getFilenameWithoutExtension();

            $guards = [
                'Permission' => 'admin',
                'Role' => 'admin'
            ];

            $guardName = 'web'; // Default guard

            foreach ($guards as $keyword => $guard) {
                if (str_contains($controllerClass, $keyword)) {
                    $guardName = $guard;
                    break; // Stop once we find the first match
                }
            }

            if (class_exists($controllerClass)) {
                $reflection = new ReflectionClass($controllerClass);
                $methods = $reflection->getMethods(\ReflectionMethod::IS_PUBLIC);

                foreach ($methods as $method) {
                    if ($method->class === $controllerClass) {
                        $permission = $reflection->getShortName() . '@' . $method->name;

                        $controllerName = substr($permission, 0, strpos($permission, 'Controller')); // Extracts the controller name without 'Controller'
                        $method = substr($permission, strpos($permission, '@') + 1); // Gets everything after '@'
                        $label = $controllerName . '-' . $method;

                        DB::table('permissions')->insert([
                            'name' => $permission,
                            'slug' => $label,
                            'guard_name' => $guardName, // Dynamically set guard name
                            'module' => $controllerName, // Dynamically set guard name
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]);

                        $this->info("Inserted permission: {$permission} with guard: {$guardName}");
                    }
                }
            }
        }

        $this->info('Permissions generated successfully!');
    }
}
