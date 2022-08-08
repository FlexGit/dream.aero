<?php

namespace App\Http\Controllers;

use App\Models\Location;
use App\Models\User;
use Auth;
use Illuminate\Http\Request;
use Validator;

class UserController extends Controller
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
		return view('admin.user.index', [
			'roles' => User::ROLES,
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
		
		$users = User::where('city_id', $city->id)
			->orderBy('lastname')
			->orderBy('name');
		if ($this->request->role) {
			$users = $users->where('role', $this->request->role);
		}
		$users = $users->get();

		$VIEW = view('admin.user.list', [
			'users' => $users,
			'roles' => User::ROLES,
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

		$user = Auth::user();
		$city = $user->city;
		
		if (!$user->isSuperAdmin()) {
			return response()->json(['status' => 'error', 'reason' => trans('main.error.недостаточно-прав-доступа')]);
		}
		
		$locations = Location::where('city_id', $city->id)
			->where('is_active', true)
			->orderBy('name')
			->get();
		
		$user = User::find($id);
		
		$VIEW = view('admin.user.modal.edit', [
			'user' => $user,
			'roles' => User::ROLES,
			'locations' => $locations,
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
		
		$user = Auth::user();
		$city = $user->city;
		
		if (!$user->isSuperAdmin()) {
			return response()->json(['status' => 'error', 'reason' => trans('main.error.недостаточно-прав-доступа')]);
		}
		
		$locations = Location::where('city_id', $city->id)
			->where('is_active', true)
			->orderBy('name')
			->get();

		$VIEW = view('admin.user.modal.add', [
			'roles' => User::ROLES,
			'locations' => $locations,
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
		
		$user = User::find($id);
		if (!$user) return response()->json(['status' => 'error', 'reason' => trans('main.error.пользователь-не-найден')]);
		
		$roles = User::ROLES;

		$VIEW = view('admin.user.modal.show', [
			'user' => $user,
			'roles' => $roles,
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
		
		$user = User::find($id);
		if (!$user) return response()->json(['status' => 'error', 'reason' => trans('main.error.пользователь-не-найден')]);
		
		$VIEW = view('admin.user.modal.delete', [
			'user' => $user,
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
		
		$user = Auth::user();
		$city = $user->city;
		
		if (!$user->isSuperAdmin()) {
			return response()->json(['status' => 'error', 'reason' => trans('main.error.недостаточно-прав-доступа')]);
		}

		$rules = [
			'name' => ['required', 'max:255'],
			'email' => ['required', 'email'],
			'role' => ['required'],
			'location_id' => ['required'],
		];
		
		$validator = Validator::make($this->request->all(), $rules)
			->setAttributeNames([
				'name' => 'Name',
				'email' => 'E-mail',
				'role' => 'Role',
				'location_id' => 'Location',
			]);
		if (!$validator->passes()) {
			return response()->json(['status' => 'error', 'reason' => $validator->errors()->all()]);
		}
		
		$user = new User();
		$user->lastname = $this->request->lastname;
		$user->name = $this->request->name;
		$user->email = $this->request->email;
		$user->password = '';
		$user->role = $this->request->role;
		$user->city_id = $city->id;
		$user->location_id = $this->request->location_id;
		$user->enable = (bool)$this->request->enable;
		if (!$user->save()) {
			return response()->json(['status' => 'error', 'reason' => trans('main.error.повторите-позже')]);
		}
		
		return response()->json(['status' => 'success']);
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
		
		$user = Auth::user();
		$city = $user->city;
		
		if (!$user->isSuperAdmin()) {
			return response()->json(['status' => 'error', 'reason' => trans('main.error.недостаточно-прав-доступа')]);
		}
		
		$rules = [
			'name' => ['required', 'max:255'],
			'email' => ['required', 'email'],
			'role' => ['required'],
			'location_id' => ['required'],
		];
		
		$validator = Validator::make($this->request->all(), $rules)
			->setAttributeNames([
				'name' => 'Name',
				'email' => 'E-mail',
				'role' => 'Role',
				'location_id' => 'Location',
			]);
		if (!$validator->passes()) {
			return response()->json(['status' => 'error', 'reason' => $validator->errors()->all()]);
		}
		
		$user = User::find($id);
		$user->lastname = $this->request->lastname;
		$user->name = $this->request->name;
		$user->email = $this->request->email;
		$user->role = $this->request->role;
		$user->city_id = $city->id;
		$user->location_id = $this->request->location_id;
		$user->enable = (bool)$this->request->enable;
		if (!$user->save()) {
			return response()->json(['status' => 'error', 'reason' => trans('main.error.повторите-позже')]);
		}
		
		return response()->json(['status' => 'success']);
	}
	
	public function passwordResetNotification($id)
	{
		if (!$this->request->ajax()) {
			abort(404);
		}
		
		if (!$this->request->user()->isSuperAdmin()) {
			return response()->json(['status' => 'error', 'reason' => trans('main.error.недостаточно-прав-доступа')]);
		}
		
		$user = User::find($id);
		if (!$user) return response()->json(['status' => 'error', 'reason' => trans('main.error.пользователь-не-найден')]);
		
		return response()->json(['status' => 'success']);
	}
	
	/**
	 * @param $id
	 * @return \Illuminate\Http\JsonResponse
	 */
	/*public function delete($id)
	{
		if (!$this->request->ajax()) {
			abort(404);
		}
		
		if (!$this->request->user()->isSuperAdmin()) {
			return response()->json(['status' => 'error', 'reason' => trans('main.error.недостаточно-прав-доступа')]);
		}
		
		$user = User::find($id);
		if (!$user) return response()->json(['status' => 'error', 'reason' => trans('main.error.пользователь-не-найден')]);

		if (in_array($user->id, [1])) {
			return response()->json(['status' => 'error', 'reason' => trans('main.error.запрещено-удаление-данного-пользователя')]);
		}
		
		if (!$user->delete()) {
			return response()->json(['status' => 'error', 'reason' => trans('main.error.повторите-позже')]);
		}
		
		return response()->json(['status' => 'success']);
	}*/
}
