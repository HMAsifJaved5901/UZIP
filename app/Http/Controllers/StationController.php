<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\Service;
use App\Models\StationService;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Station;
use App\Http\Requests\Stations\Index;
use App\Http\Requests\Stations\Show;
use App\Http\Requests\Stations\Create;
use App\Http\Requests\Stations\Store;
use App\Http\Requests\Stations\Edit;
use App\Http\Requests\Stations\Update;
use App\Http\Requests\Stations\Destroy;
use Illuminate\Support\Facades\DB;

/**
 * Description of StationController
 *
 * @author Tuhin Bepari <digitaldreams40@gmail.com>
 */
class StationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param  Index $request
     * @return \Illuminate\Http\Response
     */
    public function index(Index $request)
    {
        $companies = Company::select(['id', 'company_name'])->where('is_active', 1)->get();
        $users = User::select(['id', 'name'])->where('status', 1)->get();
        $businessHours = config('general.business_hours');
        return view('pages.stations.index',
            [
                'records' => Station::paginate(10),
                'companies' => $companies,
                'users' => $users,
                'businessHours' => $businessHours
            ]);
    }

    public function stationIndex(Request $request)
    {
        $model = Station::join('users', 'stations.manager_id', '=', 'users.id')
            ->join('companies', 'stations.company_id', '=', 'companies.id')
            ->join('categories', 'stations.category_id', '=', 'categories.id')
            ->select('stations.*', 'users.name as manager_name', 'companies.company_name as company_name', 'categories.name as category_name')
            ->get();
        $formattedModel = $model->map(function ($m) {
            return [
                'id' => $m->id,
                'category_name' => $m->category_name,
                'name' => $m->name,
                'code' => $m->code,
                'company_name' => $m->company_name,
                'manager_name' => $m->manager_name,
                'location' => $m->location,
                'latitude' => $m->latitude,
                'longitude' => $m->longitude,
                'phone' => $m->phone,
                'opening_hours' => $m->opening_hours,
                'is_active' => $m->is_active,
                'is_deleted' => $m->is_deleted,
                'created_at' => $m->created_at
            ];
        });
        // Return the data in the requested structure
        return response()->json(['data' => $formattedModel]);
    }

    public function getStationById($id)
    {
        $station = Station::where('id', $id)->first();
        return response()->json(['data' => $station]);
    }

    public function getManagers(Request $request)
    {
        $companyId = $request->company_id;

        // Fetch users based on the company
        $managers = User::where('company_id', $companyId)->get();

        return response()->json($managers);
    }

    /**
     * Display the specified resource.
     *
     * @param  Show $request
     * @param  Station $station
     * @return \Illuminate\Http\Response
     */
    public function show(Show $request, Station $station)
    {
        return view('pages.stations.show', [
            'record' => $station,
        ]);

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Store $request
     * @return \Illuminate\Http\Response
     */
    public function store(Store $request)
    {
        // Validate the unique code
        $request->validate([
            'code' => 'required|unique:stations,code,' . ($request->id ?? 'NULL') . ',id',  // Unique check for code, excluding current record
        ]);

        // Get the data and filter out null values
        $data = array_filter($request->all(), function ($value) {
            return $value !== null;
        });

        // If there is an ID, we are updating, else we are creating a new record
        $model = $request->id ? Station::find($request->id) : new Station;

        if ($model) {
            $model->fill($data);

            // Save the model and return the appropriate response
            if ($model->save()) {
                session()->flash('app_message', 'Station ' . ($request->id ? 'updated' : 'saved') . ' successfully');
                return redirect()->route('station.index');
            } else {
                session()->flash('app_message', 'Something went wrong while saving the Station');
            }
        } else {
            session()->flash('app_message', 'Station not found');
        }

        return redirect()->back();
    }


    /**
     * Delete a  resource from  storage.
     *
     * @param  Request $request
     * @return \Illuminate\Http\Response
     * @throws \Exception
     */
    public function destroy(Request $request)
    {
        $model = Station::findOrFail($request->station_id);
        $deleted = $model->is_deleted == 1 ? 0 : 1;
        $model->is_deleted = $deleted;

        if ($model->save()) {
            if ($deleted == 1) {
                return response()->json(['message' => 'Station deleted successfully.', 200]);
            } else {
                return response()->json(['message' => 'Station Restored deleted successfully.', 200]);
            }
        } else {
            return redirect()->back()->with('error', 'Error occurred while deleting Data!');
        }
    }

    public function updateStatus(Request $request)
    {
        $request->validate([
            'station_id' => ['required', 'exists:stations,id'], // Ensure the user exists
        ]);

        $id = $request->station_id;

        // Find the user to be operated
        $model = Station::findOrFail($id);

        // Perform status call by marking the user as active/inactive
        $model->is_active = $model->is_active == 1 ? 0 : 1;
        $model->save();

        return response()->json(['message' => 'Station status updated successfully.', 200]);
    }

//    ==============================Station Service Functionality================================================

    public function stationServiceIndex($id)
    {
        $model = Station::join('users', 'stations.manager_id', '=', 'users.id')
            ->join('companies', 'stations.company_id', '=', 'companies.id')
            ->join('categories', 'stations.category_id', '=', 'categories.id')
            ->select('stations.*', 'users.name as manager_name', 'companies.company_name', 'categories.name as category_name')
            ->where('stations.id', $id)
            ->first();

        $services = Service::leftJoin('station_services', 'station_services.service_id', '=', 'services.id')
            ->leftJoin('stations', 'stations.id', '=', 'station_services.station_id')
            ->where(function ($query) use ($id) {
                $query->where('stations.id', $id)// Filter by specific station ID
                ->orWhereNull('station_services.station_id'); // Include services that are not linked to the station
            })
            ->where('services.is_active', 1)// Only active services
            ->where('services.is_deleted', 0)// Exclude deleted services
            ->select('services.id', 'services.name',
                DB::raw('IF(station_services.station_id IS NOT NULL, "1", "0") as checked')
            )
            ->get();

        return view('cards.station_service', [
            'station' => $model,
            'services' => $services
        ]);
    }

    public function stationServiceStore(Request $request)
    {
        // If there is an ID, we are updating, else we are creating a new record
        $station_id = $request->station_id;
        $service_id = $request->service_id;
        $model = StationService::where('station_id', $station_id)->where('service_id', $service_id)->first();

        if ($model) {
            $model->delete();

            session()->flash('app_message', 'Station Service deleted successfully!');
        } else {
            $model = new StationService();
            $model->station_id = $station_id;
            $model->service_id = $service_id;
            $model->save();
            session()->flash('app_message', 'Station Service saved successfully!');
        }

        return redirect()->back();
    }
}
