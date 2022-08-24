<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
use App\Models\FlightSimulator;

class FlightSimulatorController extends Controller
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
		return view('admin.flightSimulator.index', [
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

		$flightSimulators = FlightSimulator::get();

		$VIEW = view('admin.flightSimulator.list', ['flightSimulators' => $flightSimulators]);

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

		$flightSimulator = FlightSimulator::find($id);
		if (!$flightSimulator) return response()->json(['status' => 'error', 'reason' => 'Simulator not found']);
		
		$VIEW = view('admin.flightSimulator.modal.edit', [
			'flightSimulator' => $flightSimulator,
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
		
		$VIEW = view('admin.flightSimulator.modal.add', [
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
		
		$flightSimulator = FlightSimulator::find($id);
		if (!$flightSimulator) return response()->json(['status' => 'error', 'reason' => 'Simulator not found']);
		
		$VIEW = view('admin.flightSimulator.modal.show', [
			'flightSimulator' => $flightSimulator,
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

		$flightSimulator = FlightSimulator::find($id);
		if (!$flightSimulator) return response()->json(['status' => 'error', 'reason' => 'Simulator not found']);
		
		$VIEW = view('admin.flightSimulator.modal.delete', [
			'flightSimulator' => $flightSimulator,
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
			'name' => ['required', 'max:255', 'unique:flight_simulators,name'],
			'alias' => ['required', 'min:3', 'max:50', 'unique:flight_simulators,alias'],
		];
		
		$validator = Validator::make($this->request->all(), $rules)
			->setAttributeNames([
				'name' => 'Name',
				'alias' => 'Alias',
			]);
		if (!$validator->passes()) {
			return response()->json(['status' => 'error', 'reason' => $validator->errors()->all()]);
		}

		$flightSimulator = new FlightSimulator();
		$flightSimulator->name = $this->request->name;
		$flightSimulator->alias = $this->request->alias;
		$flightSimulator->is_active = $this->request->is_active;
		if (!$flightSimulator->save()) {
			return response()->json(['status' => 'error', 'reason' => trans('main.error.повторите-позже')]);
		}
		
		return response()->json(['status' => 'success', 'message' => 'Simulator was successfully created']);
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

		$flightSimulator = FlightSimulator::find($id);
		if (!$flightSimulator) return response()->json(['status' => 'error', 'reason' => 'Simulator not found']);
		
		$rules = [
			'name' => ['required', 'max:255', 'unique:flight_simulators,name,' . $id],
			'alias' => ['required', 'min:3', 'max:50', 'unique:flight_simulators,alias,' . $id],
		];
		
		$validator = Validator::make($this->request->all(), $rules)
			->setAttributeNames([
				'name' => 'Name',
				'alias' => 'Alias',
			]);
		if (!$validator->passes()) {
			return response()->json(['status' => 'error', 'reason' => $validator->errors()->all()]);
		}

		$flightSimulator->name = $this->request->name;
		$flightSimulator->alias = $this->request->alias;
		$flightSimulator->is_active = $this->request->is_active;
		if (!$flightSimulator->save()) {
			return response()->json(['status' => 'error', 'reason' => trans('main.error.повторите-позже')]);
		}
		
		return response()->json(['status' => 'success', 'message' => 'Simulator was successfully saved']);
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

		$flightSimulator = FlightSimulator::find($id);
		if (!$flightSimulator) return response()->json(['status' => 'error', 'reason' => 'Simulator not found']);
		
		if (!$flightSimulator->delete()) {
			return response()->json(['status' => 'error', 'reason' => trans('main.error.повторите-позже')]);
		}
		
		return response()->json(['status' => 'success', 'message' => 'Simulator was successfully deleted']);
	}
}
