<?php

namespace App\Http\Controllers;

use App\Models\FlightSimulator;
use Auth;
use Illuminate\Http\Request;
use Validator;
use App\Models\Location;
use App\Models\City;
use App\Models\LegalEntity;

class LocationController extends Controller
{
	private $request;
	
	/**
	 * @param Request $request
	 */
	public function __construct(Request $request) {
		$this->request = $request;
	}
	
	/**
	 * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
	 */
	public function index()
	{
		$cities = City::orderBy('name')
			->get();
		
		/*$legalEntities = LegalEntity::where('is_active', true)
			->orderBy('name', 'asc')
			->get();*/

		return view('admin.location.index', [
			'cities' => $cities,
			/*'legalEntities' => $legalEntities,*/
		]);
	}
	
	/**
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function getListAjax()
	{
		if (!$this->request->ajax()) {
			abort(404);
		}
		
		$user = Auth::user();
		$city = $user->city;

		$locations = Location::where('city_id', $city->id)
			->get();

		$VIEW = view('admin.location.list', [
			'locations' => $locations,
			'city' => $city,
		]);

		return response()->json(['status' => 'success', 'html' => (string)$VIEW]);
	}
	
	/**
	 * @param $id
	 * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\JsonResponse
	 */
	public function edit($id)
	{
		if (!$this->request->ajax()) {
			abort(404);
		}
		
		if (!$this->request->user()->isSuperAdmin()) {
			return response()->json(['status' => 'error', 'reason' => 'Недостаточно прав доступа']);
		}

		$location = Location::find($id);
		if (!$location) return response()->json(['status' => 'error', 'reason' => 'Локация не найдена']);
		
		$cities = City::where('is_active', true)
			->orderBy('name', 'asc')
			->get();
		
		/*$legalEntities = LegalEntity::where('is_active', true)
			->orderBy('name', 'asc')
			->get();*/

		$simulators = FlightSimulator::get();
		
		$VIEW = view('admin.location.modal.edit', [
			'location' => $location,
			'cities' => $cities,
			/*'legalEntities' => $legalEntities,*/
			'simulators' => $simulators,
		]);
		
		return response()->json(['status' => 'success', 'html' => (string)$VIEW]);
	}
	
	/**
	 * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
	 */
	public function add()
	{
		if (!$this->request->ajax()) {
			abort(404);
		}
		
		if (!$this->request->user()->isSuperAdmin()) {
			return response()->json(['status' => 'error', 'reason' => 'Недостаточно прав доступа']);
		}

		$cities = City::where('is_active', true)
			->orderBy('name', 'asc')
			->get();
		
		/*$legalEntities = LegalEntity::where('is_active', true)
			->orderBy('name', 'asc')
			->get();*/

		$VIEW = view('admin.location.modal.add', [
			'cities' => $cities,
			/*'legalEntities' => $legalEntities,*/
		]);
		
		return response()->json(['status' => 'success', 'html' => (string)$VIEW]);
	}
	
	/**
	 * @param $id
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function show($id)
	{
		if (!$this->request->ajax()) {
			abort(404);
		}
		
		$location = Location::find($id);
		if (!$location) return response()->json(['status' => 'error', 'reason' => 'Локация не найдена']);
		
		$VIEW = view('admin.location.modal.show', [
			'location' => $location,
		]);
		
		return response()->json(['status' => 'success', 'html' => (string)$VIEW]);
	}

	/**
	 * @param $id
	 * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\JsonResponse
	 */
	public function confirm($id)
	{
		if (!$this->request->ajax()) {
			abort(404);
		}

		if (!$this->request->user()->isSuperAdmin()) {
			return response()->json(['status' => 'error', 'reason' => 'Недостаточно прав доступа']);
		}

		$location = Location::find($id);
		if (!$location) return response()->json(['status' => 'error', 'reason' => 'Локация не найдена']);
		
		$VIEW = view('admin.location.modal.delete', [
			'location' => $location,
		]);
		
		return response()->json(['status' => 'success', 'html' => (string)$VIEW]);
	}
	
	/**
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function store()
	{
		if (!$this->request->ajax()) {
			abort(404);
		}
		
		if (!$this->request->user()->isSuperAdmin()) {
			return response()->json(['status' => 'error', 'reason' => 'Недостаточно прав доступа']);
		}

		$rules = [
			'name' => 'required|max:255|unique:locations,name',
			'alias' => 'required|min:2|max:25|unique:locations,alias',
			/*'legal_entity_id' => 'required|integer',*/
			'city_id' => 'required|integer',
			'address' => 'required',
			'working_hours' => 'required',
			'phone' => 'required',
			'email' => 'required|email',
			'scheme_file' => 'sometimes|image|max:512|mimes:jpg,jpeg,png,webp',
		];
		
		$validator = Validator::make($this->request->all(), $rules)
			->setAttributeNames([
				'name' => 'Наименование',
				'alias' => 'Алиас',
				/*'legal_entity_id' => 'Юр.лицо',*/
				'city_id' => 'Город',
				'address' => 'Адрес',
				'working_hours' => 'Часы работы',
				'phone' => 'Телефон',
				'email' => 'E-mail',
				'scheme_file' => 'План-схема',
			]);
		if (!$validator->passes()) {
			return response()->json(['status' => 'error', 'reason' => $validator->errors()->all()]);
		}
		
		$isFileUploaded = false;
		if($file = $this->request->file('scheme_file')) {
			$isFileUploaded = $file->move(public_path('upload/scheme'), $file->getClientOriginalName());
		}

		$location = new Location();
		$location->name = $this->request->name;
		$location->alias = $this->request->alias;
		$location->is_active = $this->request->is_active;
		/*$location->legal_entity_id = $this->request->legal_entity_id;*/
		$location->city_id = $this->request->city_id;
		$location->data_json = [
			'address' => $this->request->address,
			'working_hours' => $this->request->working_hours,
			'phone' => $this->request->phone,
			'email' => $this->request->email,
			'map_link' => $this->request->map_link,
			'skype' => $this->request->skype,
			'whatsapp' => $this->request->whatsapp,
			'scheme_file_path' => $isFileUploaded ? 'scheme/' . $file->getClientOriginalName() : '',
		];
		if (!$location->save()) {
			return response()->json(['status' => 'error', 'reason' => 'В данный момент невозможно выполнить операцию, повторите попытку позже!']);
		}
		
		return response()->json(['status' => 'success', 'id' => $location->id]);
	}
	
	/**
	 * @param $id
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function update($id)
	{
		if (!$this->request->ajax()) {
			abort(404);
		}
		
		if (!$this->request->user()->isSuperAdmin()) {
			return response()->json(['status' => 'error', 'reason' => 'Недостаточно прав доступа']);
		}

		$location = Location::find($id);
		if (!$location) return response()->json(['status' => 'error', 'reason' => 'Локация не найдена']);
		
		$rules = [
			'name' => 'required|max:255|unique:locations,name,' . $id,
			'alias' => 'required|min:2|max:25|unique:locations,alias,' . $id,
			/*'legal_entity_id' => 'required|integer',*/
			'city_id' => 'required|integer',
			'address' => 'required',
			'working_hours' => 'required',
			'phone' => 'required',
			'email' => 'required|email',
			'scheme_file' => 'sometimes|image|max:1024|mimes:jpg,jpeg,png,webp',
		];
		
		$validator = Validator::make($this->request->all(), $rules)
			->setAttributeNames([
				'name' => 'Наименование',
				'alias' => 'Алиас',
				/*'legal_entity_id' => 'Юр.лицо',*/
				'city_id' => 'Город',
				'address' => 'Адрес',
				'working_hours' => 'Часы работы',
				'phone' => 'Телефон',
				'email' => 'E-mail',
				'scheme_file' => 'План-схема',
			]);
		if (!$validator->passes()) {
			return response()->json(['status' => 'error', 'reason' => $validator->errors()->all()]);
		}

		$isFileUploaded = false;
		if($file = $this->request->file('scheme_file')) {
			$isFileUploaded = $file->move(public_path('upload/scheme'), $file->getClientOriginalName());
		}
			
		$location->name = $this->request->name;
		$location->alias = $this->request->alias;
		$location->is_active = $this->request->is_active;
		/*$location->legal_entity_id = $this->request->legal_entity_id;*/
		$location->city_id = $this->request->city_id;
		
		$data = $location->data_json;
		$data['address'] = $this->request->address;
		$data['working_hours'] = $this->request->working_hours;
		$data['phone'] = $this->request->phone;
		$data['email'] = $this->request->email;
		$data['map_link'] = $this->request->map_link;
		$data['skype'] = $this->request->skype;
		$data['whatsapp'] = $this->request->whatsapp;
		$data['scheme_file_path'] = $isFileUploaded ? 'scheme/' . $file->getClientOriginalName() : '';
		$location->data_json = $data;

		if (!$location->save()) {
			return response()->json(['status' => 'error', 'reason' => 'В данный момент невозможно выполнить операцию, повторите попытку позже!']);
		}

		$colors = $this->request->color ?? [];
		$letterNames = $this->request->letter_name ?? [];
		$locationSimulatorData = [];
		foreach (array_keys($this->request->simulator) ?? [] as $simulatorId) {
			$locationSimulatorData[$simulatorId]['data_json'] = [];
			foreach ($colors[$simulatorId] as $eventType => $color) {
				$locationSimulatorData[$simulatorId]['data_json'][$eventType] = $color ?? '';
			}
			$locationSimulatorData[$simulatorId]['data_json']['letter_name'] = isset($letterNames[$simulatorId]) ? $letterNames[$simulatorId] : '';
			$locationSimulatorData[$simulatorId]['data_json'] = json_encode($locationSimulatorData[$simulatorId]['data_json'], JSON_UNESCAPED_UNICODE);
		}

		$location->simulators()->sync($locationSimulatorData);
		
		return response()->json(['status' => 'success', 'id' => $location->id]);
	}
	
	/**
	 * @param $id
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function delete($id)
	{
		if (!$this->request->ajax()) {
			abort(404);
		}
		
		if (!$this->request->user()->isSuperAdmin()) {
			return response()->json(['status' => 'error', 'reason' => 'Недостаточно прав доступа']);
		}

		$location = Location::find($id);
		if (!$location) return response()->json(['status' => 'error', 'reason' => 'Локация не найдена']);
		
		if (!$location->delete()) {
			return response()->json(['status' => 'error', 'reason' => 'В данный момент невозможно выполнить операцию, повторите попытку позже!']);
		}
		
		return response()->json(['status' => 'success', 'id' => $location->id]);
	}
}
