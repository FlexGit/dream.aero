<?php

namespace App\Http\Controllers;

use App\Services\HelpFunctions;
use Auth;
use Illuminate\Http\Request;
use Validator;
use App\Models\City;
use App\Models\User;

class CityController extends Controller
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
		return view('admin.city.index', [
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
		
		$cities = City::where('id', $city->id)
			->get();
		
		$VIEW = view('admin.city.list', ['cities' => $cities]);

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
			return response()->json(['status' => 'error', 'reason' => trans('main.error.недостаточно-прав-доступа')]);
		}

		$city = City::find($id);
		if (!$city) return response()->json(['status' => 'error', 'reason' => trans('main.error.город-не-найден')]);

		$VIEW = view('admin.city.modal.edit', [
			'city' => $city,
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
			return response()->json(['status' => 'error', 'reason' => trans('main.error.недостаточно-прав-доступа')]);
		}

		$VIEW = view('admin.city.modal.add', [
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
		
		$city = City::find($id);
		if (!$city) return response()->json(['status' => 'error', 'reason' => trans('main.error.город-не-найден')]);
		
		$VIEW = view('admin.city.modal.show', [
			'city' => $city,
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
			return response()->json(['status' => 'error', 'reason' => trans('main.error.недостаточно-прав-доступа')]);
		}

		$city = City::find($id);
		if (!$city) return response()->json(['status' => 'error', 'reason' => trans('main.error.город-не-найден')]);
		
		$VIEW = view('admin.city.modal.delete', [
			'city' => $city,
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
			return response()->json(['status' => 'error', 'reason' => trans('main.error.недостаточно-прав-доступа')]);
		}

		$rules = [
			'name' => 'required|max:255|unique:cities,name',
			'alias' => 'required|min:2|max:3|unique:cities,alias',
			'email' => 'required|email',
			'phone' => 'required',
		];
		
		$validator = Validator::make($this->request->all(), $rules)
			->setAttributeNames([
				'name' => 'Наименование',
				'alias' => 'Алиас',
				'email' => 'E-mail',
				'phone' => 'Телефон',
			]);
		if (!$validator->passes()) {
			return response()->json(['status' => 'error', 'reason' => $validator->errors()->all()]);
		}
		
		$city = new City();
		$city->name = $this->request->name;
		$city->alias = $this->request->alias;
		$city->email = $this->request->email;
		$city->phone = $this->request->phone;
		$city->is_active = $this->request->is_active;
		if (!$city->save()) {
			return response()->json(['status' => 'error', 'reason' => trans('main.error.повторите-позже')]);
		}
		
		return response()->json(['status' => 'success', 'message' => 'City was successfully created']);
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
			return response()->json(['status' => 'error', 'reason' => trans('main.error.недостаточно-прав-доступа')]);
		}

		$city = City::find($id);
		if (!$city) return response()->json(['status' => 'error', 'reason' => trans('main.error.город-не-найден')]);
		
		if (HelpFunctions::isDemo($city->created_at)) {
			return response()->json(['status' => 'error', 'reason' => 'Demo data cannot be updated']);
		}
		
		$rules = [
			'name' => 'required|max:255|unique:cities,name,' . $id,
			'alias' => 'required|min:2|max:3|unique:cities,alias,' . $id,
			'email' => 'required|email',
			'phone' => 'required',
		];
		
		$validator = Validator::make($this->request->all(), $rules)
			->setAttributeNames([
				'name' => 'Name',
				'alias' => 'Alias',
				'email' => 'E-mail',
				'phone' => 'Phone number',
			]);
		if (!$validator->passes()) {
			return response()->json(['status' => 'error', 'reason' => $validator->errors()->all()]);
		}

		$city->name = $this->request->name;
		$city->alias = $this->request->alias;
		$city->email = $this->request->email;
		$city->phone = $this->request->phone;
		$city->is_active = $this->request->is_active;
		if (!$city->save()) {
			return response()->json(['status' => 'error', 'reason' => trans('main.error.повторите-позже')]);
		}
		
		return response()->json(['status' => 'success', 'message' => 'City was successfully updated']);
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
			return response()->json(['status' => 'error', 'reason' => trans('main.error.недостаточно-прав-доступа')]);
		}

		$city = City::find($id);
		if (!$city) return response()->json(['status' => 'error', 'reason' => trans('main.error.город-не-найден')]);
		
		if (HelpFunctions::isDemo($city->created_at)) {
			return response()->json(['status' => 'error', 'reason' => 'Demo data cannot be deleted']);
		}
		if (!$city->delete()) {
			return response()->json(['status' => 'error', 'reason' => trans('main.error.повторите-позже')]);
		}
		
		return response()->json(['status' => 'success']);
	}
	
	/**
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function getUserList()
	{
		if (!$this->request->ajax()) {
			abort(404);
		}
		
		if (!$this->request->user()->isSuperAdmin()) {
			return response()->json(['status' => 'error', 'reason' => trans('main.error.недостаточно-прав-доступа')]);
		}

		$userData = [];

		if ($this->request->cityId) {
			$city = City::find($this->request->cityId);
			if (!$city) return response()->json(['status' => 'error', 'reason' => trans('main.error.город-не-найден')]);
			
			foreach ($city->location ?? [] as $location) {
				$users = $location->user;
				foreach ($users as $user) {
					if (!$user->is_active) continue;
					
					$userData[] = [
						'id' => $user->id,
						'name' => $user->name,
					];
				}
			}
		} else {
			$users = User::where('is_active', true)
				->orderBy('name', 'asc')
				->get();
			foreach ($users as $user) {
				$userData[] = [
					'id' => $user->id,
					'name' => $user->name,
				];
			}
		}

		usort($userData, function($a, $b) {
			return $a['name'] <=> $b['name'];
		});

		return response()->json(['status' => 'success', 'users' => $userData]);
	}
}
