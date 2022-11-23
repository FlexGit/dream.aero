<?php

namespace App\Http\Controllers;

use App\Models\Content;
use App\Models\Status;
use App\Models\Contractor;
use App\Models\City;
use App\Services\HelpFunctions;
use Auth;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Validator;

class ContractorController extends Controller
{
	private $request;
	
	/**
	 * @param Request $request
	 */
	public function __construct(Request $request) {
		$this->request = $request;
	}
	
	/**
	 * @param null $contractorId
	 * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
	 */
	public function index($contractorId = null)
	{
		if ($contractorId) {
			$contractor = Contractor::find($contractorId);
		}
		
		$page = HelpFunctions::getEntityByAlias(Content::class, 'contractors');
		
		return view('admin.contractor.index', [
			'contractor' => $contractor ?? null,
			'page' => $page,
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
		
		$id = $this->request->id ?? 0;
		
		$contractors = Contractor::where('city_id', $city->id)
			->orderBy('created_at', 'desc');
		if ($this->request->search_contractor) {
			$contractors = $contractors->where(function ($query) {
				$query->where('name', 'like', '%' . $this->request->search_contractor . '%')
					->orWhere('lastname', 'like', '%' . $this->request->search_contractor . '%')
					->orWhere('email', 'like', '%' . $this->request->search_contractor . '%')
					->orWhere('phone', 'like', '%' . $this->request->search_contractor . '%')
					->orWhere('uuid', $this->request->search_contractor);
			});
		}
		if ($id) {
			$contractors = $contractors->where('id', '<', $id);
		}
		$contractors = $contractors->limit(env('LIST_LIMIT'))->get();
		
		$statuses = Status::where('is_active', true)
			->get();

		$VIEW = view('admin.contractor.list', [
			'contractors' => $contractors,
			'statuses' => $statuses,
			'user' => $user,
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
		
		$contractor = Contractor::find($id);
		if (!$contractor) return response()->json(['status' => 'error', 'reason' => trans('main.error.контрагент-не-найден')]);

		$cities = City::orderBy('name');
		$cities = $cities->get();
		
		$VIEW = view('admin.contractor.modal.edit', [
			'contractor' => $contractor,
			'cities' => $cities,
		]);
		
		return response()->json(['status' => 'success', 'html' => (string)$VIEW]);
	}

	/**
	 * @param $id
	 * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\JsonResponse
	 */
	public function unite($id)
	{
		if (!$this->request->ajax()) {
			abort(404);
		}

		$user = Auth::user();

		if (!$user->isSuperAdmin()) {
			return response()->json(['status' => 'error', 'reason' => trans('main.error.недостаточно-прав-доступа')]);
		}

		$contractor = Contractor::find($id);
		if (!$contractor) return response()->json(['status' => 'error', 'reason' => trans('main.error.контрагент-не-найден')]);

		$statuses = Status::where('is_active', true)
			->get();

		$VIEW = view('admin.contractor.modal.Unite', [
			'contractor' => $contractor,
			'statuses' => $statuses,
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
		
		$cities = City::orderBy('name');
		$cities = $cities->get();

		$VIEW = view('admin.contractor.modal.add', [
			'cities' => $cities,
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

		$rules = [
			'name' => 'required',
			'email' => 'required|email|unique_email',
		];
		
		$validator = Validator::make($this->request->all(), $rules)
			->setAttributeNames([
				'name' => 'Name',
				'email' => 'E-mail',
			]);
		if (!$validator->passes()) {
			return response()->json(['status' => 'error', 'reason' => $validator->errors()->all()]);
		}

		$birthdate = $this->request->birthdate ?? '';

		$contractor = new Contractor();
		$contractor->name = $this->request->name;
		$contractor->lastname = $this->request->lastname;
		$contractor->email = $this->request->email;
		$contractor->phone = $this->request->phone ?? null;
		$contractor->city_id = $city ? $city->id : 0;
		$contractor->birthdate = $birthdate ? Carbon::parse($birthdate)->format('Y-m-d') : null;
		$contractor->source = Contractor::ADMIN_SOURCE;
		$contractor->is_active = (bool)$this->request->is_active;
		$contractor->user_id = $user->id;
		if (!$contractor->save()) {
			return response()->json(['status' => 'error', 'reason' => trans('main.error.повторите-позже')]);
		}
		
		return response()->json(['status' => 'success', 'message' => 'Contractor was successfully created']);
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

		$contractor = Contractor::find($id);
		if (!$contractor) return response()->json(['status' => 'error', 'reason' => trans('main.error.контрагент-не-найден')]);

		if ($contractor->email == Contractor::ANONYM_EMAIL) {
			return response()->json(['status' => 'error', 'reason' => trans('main.error.контрагент-аноним-не-может-быть-изменен')]);
		}

		$rules = [
			'name' => 'required',
			'email' => 'required|email|unique_email',
		];
		
		$validator = Validator::make($this->request->all(), $rules)
			->setAttributeNames([
				'name' => 'Name',
				'email' => 'E-mail',
			]);
		if (!$validator->passes()) {
			return response()->json(['status' => 'error', 'reason' => $validator->errors()->all()]);
		}

		$birthdate = $this->request->birthdate ?? '';

		$contractor->name = $this->request->name;
		$contractor->lastname = $this->request->lastname ?? null;
		$contractor->email = $this->request->email;
		$contractor->phone = $this->request->phone ?? null;
		$contractor->birthdate = $birthdate ? Carbon::parse($birthdate)->format('Y-m-d') : null;
		$contractor->is_active = (bool)$this->request->is_active;
		if (!$contractor->save()) {
			return response()->json(['status' => 'error', 'reason' => trans('main.error.повторите-позже')]);
		}
		
		return response()->json(['status' => 'success', 'message' => 'Contractor was successfully saved']);
	}
	
	public function search() {
		\Log::debug($this->request);
		$q = $this->request->post('query');
		if (!$q) return response()->json(['status' => 'error', 'reason' => trans('main.error.нет-данных')]);
		
		$user = Auth::user();
		$city = $user->city;
		
		$contractors = Contractor::where('is_active', true)
			->where(function($query) use ($q) {
				$query->where("name", "LIKE", "%{$q}%")
					->orWhere("lastname", "LIKE", "%{$q}%")
					->orWhere("email", "LIKE", "%{$q}%")
					->orWhere("phone", "LIKE", "%{$q}%");
			})
			->whereIn('city_id', [$city->id, 0])
			->orderBy('name')
			->orderBy('lastname')
			->get();
		$suggestions = [];
		foreach ($contractors as $contractor) {
			$discount = $contractor->discount();
			$suggestions[] = [
				'value' => $contractor->name . ($contractor->lastname ? ' ' . $contractor->lastname : '') . ' [' . $contractor->email . ($contractor->phone ? ', ' . $contractor->phone : '') . (($discount && $discount->value) ? ', ' . $discount->valueFormatted() : '') . ']',
				'id' => $contractor->id,
				'data' => [
					'name' => $contractor->name,
					'lastname' => $contractor->lastname ?? '',
					'email' => $contractor->email ?? '',
					'phone' => $contractor->phone ?? '',
					'discount' => ($discount && $discount->value) ? $discount->valueFormatted() : '',
				],
			];
		}
		
		return response()->json(['suggestions' => $suggestions]);
	}
}
