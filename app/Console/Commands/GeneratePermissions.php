<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use ReflectionClass;
use Spatie\Permission\Models\Permission; // Assuming you are using Spatie's Permission package

class GeneratePermissions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'generate:permissions';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate user-friendly permissions based on controllers and their methods, distinguishing API and Web.';

    /**
     * Execute the console command.
     */

    public function handle()
    {
        $defaultGuardName = 'web';
        $controllerNamespace = 'App\\Http\\Controllers\\';
        $controllerPath = app_path('Http/Controllers');
        $controllerFiles = File::allFiles($controllerPath);

        $excludedControllers = [
            'Controller',
            'AuthController',
            'AuthenticatedSessionController',
            'ConfirmablePasswordController',
            'EmailVerificationNotificationController',
            'EmailVerificationPromptController',
            'NewPasswordController',
            'PasswordController',
            'PasswordResetLinkController',
            'RegisteredUserController',
            'VerifyEmailController',
            'CategoryController',
            'LookupValueController',
            'EmployeeWageController',
            'EmployeeAttendanceController',
            'ConfigurationController',
        ];

        $permissionsToProcess = [];

        foreach ($controllerFiles as $file) {
            $fileNameWithoutExtension = $file->getFilenameWithoutExtension();
            $relativePathname = $file->getRelativePathname();

            if (in_array($fileNameWithoutExtension, $excludedControllers)) {
                $this->info("Skipped excluded controller: {$fileNameWithoutExtension}");
                continue;
            }

            $currentGuardName = $defaultGuardName;
            if ($file->getRelativePath() === 'Api') {
                $currentGuardName = 'api';
            } else {
                $currentGuardName = 'web';
            }

            $rawControllerName = str_replace('Controller', '', $fileNameWithoutExtension);
            if (str_contains($rawControllerName, 'Permission') || str_contains($rawControllerName, 'Role')) {
                $currentGuardName = 'admin';
            }

            $controllerClass = $controllerNamespace . str_replace(['/', '.php'], ['\\', ''], $relativePathname);

            if (class_exists($controllerClass)) {
                $reflection = new ReflectionClass($controllerClass);
                $methods = $reflection->getMethods(\ReflectionMethod::IS_PUBLIC);

                foreach ($methods as $method) {
                    if ($method->class === $controllerClass) {
                        $originalMethodName = $method->name;

                        $allowedPatterns = [
                            '/^(index|show|view|list|dataTable|getDataTable)(DcMart|Aloha|DcLottoOnline|VirginiaScratchCard)?$/i', // Updated for specific sub-modules
                            '/^(create|store|add)?$/i',
                            '/^(create|store|add)(DcMart|Aloha)?$/i',
                            '/^(edit|update)(DcMart|Aloha)?$/i',
                            '/^destroy(DcMart|Aloha)?$/i',
                            '/^(dcMart|Aloha)?SaleApprove/i',
                            '/^(dcMart|Aloha)?SaleReject/i',
                            '/^changeStatus/i',
                            '/^approve/i',
                            '/^reject/i',
                        ];

                        $isAllowed = false;
                        foreach ($allowedPatterns as $pattern) {
                            if (preg_match($pattern, $originalMethodName)) {
                                $isAllowed = true;
                                break;
                            }
                        }

                        if (!$isAllowed) {
                            continue;
                        }

                        // Extract specific sub-module for naming
                        $specificSubModule = '';
                        if (preg_match('/(DcLottoOnline|VirginiaScratchCard)/i', $originalMethodName, $matches)) {
                            $specificSubModule = $matches[1];
                        } elseif (preg_match('/(DcMart|Aloha)/i', $originalMethodName, $matches)) {
                            $specificSubModule = $matches[1];
                        }

                        // Determine the base user-friendly action name (View, Insert, Update, Delete)
                        $userFriendlyActionName = $this->getUserFriendlyMethodName($originalMethodName);

                        // Get the user-friendly controller name, potentially including a sub-module
                        $userFriendlyControllerBaseName = $this->getUserFriendlyControllerName($rawControllerName, $specificSubModule);

                        // Construct the full user-friendly permission name (for the 'name' field)
                        // Example: "Supplier Update", "Station Lottery DC Lotto Online View"
//                        $userFriendlyPermissionName = trim($userFriendlyControllerBaseName . ' ' . $userFriendlyActionName);
                        $userFriendlyPermissionName = trim($userFriendlyActionName);

                        // Construct the consolidated permission slug (for the 'slug' field)
                        // This will include the guard and be dash-separated
                        $consolidatedPermissionSlug = sprintf(
                            '%s-%s-%s',
                            $currentGuardName,
                            $this->camelCaseToDash($userFriendlyControllerBaseName),
                            $this->camelCaseToDash($userFriendlyActionName)
                        );
                        $consolidatedPermissionSlug = $this->removeDuplicates($consolidatedPermissionSlug);

                        // Use the full relative path, consolidated permission name, and guard_name
                        // to ensure a truly unique key for each distinct controller file and its actions.
                        $uniqueProcessingKey = md5($relativePathname . $consolidatedPermissionSlug . $currentGuardName);

                        if (!isset($permissionsToProcess[$uniqueProcessingKey])) {
                            $permissionsToProcess[$uniqueProcessingKey] = [
                                'name' => $userFriendlyPermissionName, // User-friendly display name
                                'slug' => $consolidatedPermissionSlug, // Dash-separated slug with guard
                                'module' => $userFriendlyControllerBaseName, // User-friendly module name
                                'guard_name' => $currentGuardName,
                                'created_at' => now(),
                                'updated_at' => now(),
                            ];
                        }
                    }
                }
            }
        }

        // Process permissions: insert new ones or update existing ones
        foreach ($permissionsToProcess as $permissionData) {
            // Check for existing permission using the 'slug' and 'guard_name'
            // The 'slug' is now the unique identifier for database lookup.
            $existingPermission = DB::table('permissions')
                ->where('slug', $permissionData['slug']) // Use slug for unique lookup
                ->where('guard_name', $permissionData['guard_name'])
                ->first();

            if (!$existingPermission) {
                DB::table('permissions')->insert($permissionData);
                $this->info("Inserted permission: '{$permissionData['name']}' (Slug: '{$permissionData['slug']}', Module: '{$permissionData['module']}', Guard: '{$permissionData['guard_name']}')");
            } else {
                // Permission exists, update 'name' and 'module' if they are different
                if ($existingPermission->name !== $permissionData['name'] || $existingPermission->module !== $permissionData['module']) {
                    DB::table('permissions')
                        ->where('id', $existingPermission->id)
                        ->update([
                            'name' => $permissionData['name'],
                            'module' => $permissionData['module'],
                            'updated_at' => now(),
                        ]);
                    $this->info("Updated permission: '{$permissionData['name']}' (Slug: '{$permissionData['slug']}', Module: '{$permissionData['module']}', Guard: '{$permissionData['guard_name']}')");
                } else {
                    $this->info("Skipped existing permission (no changes needed): '{$permissionData['name']}'");
                }
            }
        }

        $this->info('Permissions generation process completed!');
    }

    /**
     * Converts a raw controller name (e.g., 'AtmCashIn') to a user-friendly string (e.g., 'ATM Cash In').
     * Can also incorporate a sub-module name for more specific titles.
     *
     * @param string $rawControllerName
     * @param string $specificSubModule Optional specific sub-module name (e.g., 'DcLottoOnline', 'VirginiaScratchCard')
     * @return string
     */
    protected function getUserFriendlyControllerName($rawControllerName, $specificSubModule = '')
    {
        $baseName = '';
        switch ($rawControllerName) {
            case 'AtmCashIn': $baseName = 'Cash In'; break;
            case 'AtmCashInflow': $baseName = 'ATM Cash Inflow'; break;
            case 'CarWashSale': $baseName = 'Car Wash Sales'; break;
            case 'Cih': $baseName = 'Cash In Hand'; break;
            case 'LotterySales': $baseName = 'Lottery Sales'; break;
            case 'MechanicTransaction': $baseName = 'Mechanic Transactions'; break;
            case 'RestaurantAlohaSales': $baseName = 'Restaurant Aloha Sales'; break;
            case 'RestaurantMartSales': $baseName = 'Restaurant Mart Sales'; break;
            case 'StationCih': $baseName = 'Coins'; break;
            case 'StationLottery': $baseName = 'Station Lottery'; break;
            case 'EmployeeAttendance': $baseName = 'Employee Attendance'; break;
            case 'EmployeeWage': $baseName = 'Employee Wages'; break;
            case 'LookupValue': $baseName = 'Reference Data'; break;
            case 'MechanicSale': $baseName = 'Mechanic Sales'; break;
            case 'RestaurantSale': $baseName = 'Restaurant Sales'; break;
            default:
                $baseName = $this->camelCaseToWords($rawControllerName);
                break;
        }

        // Handle specific sub-modules that should be appended to the controller name
        if ($rawControllerName === 'StationLottery' && !empty($specificSubModule)) {
            return $baseName . ' ' . $this->camelCaseToWords($specificSubModule);
        }
        if ($rawControllerName === 'RestaurantSale' && !empty($specificSubModule)) {
            return "Restaurant " . $this->camelCaseToWords($specificSubModule) . " Sales";
        }

        return $baseName;
    }

    /**
     * Converts an original method name (e.g., 'index', 'store', 'indexDcMart') to a user-friendly string (e.g., 'View', 'Insert').
     *
     * @param string $originalMethodName
     * @return string
     */
    protected function getUserFriendlyMethodName($originalMethodName)
    {
        // Keep a copy of the original name to detect specific sub-modules later if needed for 'View'
        $cleanedMethodName = $originalMethodName;

        // Remove known suffixes/prefixes that don't affect the core action
        $cleanedMethodName = preg_replace('/(Sales|Sale|DcMart|Aloha|DcLottoOnline|VirginiaScratchCard)$/i', '', $cleanedMethodName);
        $cleanedMethodName = preg_replace('/^(dcMart|Aloha|dcLottoOnline|virginiaScratchCard)/i', '', $cleanedMethodName);

        switch (strtolower($cleanedMethodName)) {
            case 'index':
            case 'list':
            case 'view':
            case 'show':
            case 'datatablelist':
                return 'View';
            case 'create':
            case 'store':
                return 'Insert';
            case 'update':
            case 'edit':
                return 'Update';
            case 'destroy':
            case 'delete':
                return 'Delete';
            case 'approve':
                return 'Approve';
            case 'reject':
                return 'Reject';
            case 'changeStatus':
                return 'Manage Status';
            default:
                return $this->camelCaseToWords($cleanedMethodName);
        }
    }

    /**
     * Converts a camelCase string to a dash-separated lowercase string.
     * Used for creating slugs.
     * Example: 'AtmCashIn' -> 'atm-cash-in'
     *
     * @param string $string
     * @return string
     */
    protected function camelCaseToDash($string)
    {
        // Ensure only a single dash between words and remove leading/trailing dashes
        $dashed = strtolower(preg_replace('/([a-z])([A-Z])/', '$1-$2', $string));
        $dashed = preg_replace('/[^a-z0-9]+/', '-', $dashed); // Replace non-alphanumeric with single dash
        return trim($dashed, '-'); // Trim leading/trailing dashes
    }

    /**
     * Converts a camelCase string to space-separated words.
     * Used for creating user-friendly labels.
     * Example: 'AtmCashIn' -> 'Atm Cash In'
     *
     * @param string $string
     * @return string
     */
    protected function camelCaseToWords($string)
    {
        // Insert a space before each uppercase letter that is not the first letter
        $string = preg_replace('/(?<!^)([A-Z])/', ' $1', $string);
        // Replace multiple spaces with a single space
        $string = preg_replace('/\s+/', ' ', $string);
        return trim($string);
    }

    /**
     * Removes duplicate words in a dash-separated string.
     * Example: 'api-atm-cash-in-cash-in-view' -> 'api-atm-cash-in-view'
     *
     * @param string $string
     * @return string
     */
    protected function removeDuplicates($string)
    {
        $words = explode('-', $string);
        $uniqueWords = [];
        foreach ($words as $word) {
            if (!in_array($word, $uniqueWords)) {
                $uniqueWords[] = $word;
            }
        }
        return implode('-', $uniqueWords);
    }
}