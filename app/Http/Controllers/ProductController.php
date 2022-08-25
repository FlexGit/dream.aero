<?php

namespace App\Http\Controllers;

use App\Models\ProductType;
use App\Models\Product;
use App\Models\User;
use Auth;
use Illuminate\Http\Request;
use Validator;

class ProductController extends Controller
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
		return view('admin.product.index', [
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

		if (!$user->isSuperAdmin()) {
			return response()->json(['status' => 'error', 'reason' => trans('main.error.недостаточно-прав-доступа')]);
		}

		$products = Product::with(['productType'])
			->orderBy('product_type_id', 'asc')
			->orderBy('duration', 'asc');
		$products = $products->get();

		$VIEW = view('admin.product.list', [
			'products' => $products
		]);

		return response()->json(['status' => 'success', 'html' => (string)$VIEW]);
	}
	
	/**
	 * @param $id
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function edit($id)
	{
		if (!$this->request->ajax()) {
			abort(404);
		}
		
		$user = Auth::user();

		if (!$user->isSuperAdmin()) {
			return response()->json(['status' => 'error', 'reason' => trans('main.error.недостаточно-прав-доступа')]);
		}

		$product = Product::find($id);
		if (!$product) return response()->json(['status' => 'error', 'reason' => trans('main.error.продукт-не-найден')]);
		
		$productTypes = ProductType::where('is_active', true)
			->orderBy('sort')
			->get();

		$pilots = User::where('role', User::ROLE_PILOT)
			->orderBy('lastname')
			->orderBy('name')
			->get();

		$VIEW = view('admin.product.modal.edit', [
			'product' => $product,
			'productTypes' => $productTypes,
			'pilots' => $pilots,
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
		
		$user = Auth::user();
		
		if (!$user->isSuperAdmin()) {
			return response()->json(['status' => 'error', 'reason' => trans('main.error.недостаточно-прав-доступа')]);
		}

		$product = Product::find($id);
		if (!$product) return response()->json(['status' => 'error', 'reason' => trans('main.error.продукт-не-найден')]);

		$productTypes = ProductType::where('is_active', true)
			->orderBy('sort')
			->get();
		
		$VIEW = view('admin.product.modal.show', [
			'product' => $product,
			'productTypes' => $productTypes,
		]);
		
		return response()->json(['status' => 'success', 'html' => (string)$VIEW]);
	}
	
	/**
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function add()
	{
		if (!$this->request->ajax()) {
			abort(404);
		}
		
		$user = Auth::user();
		
		if (!$user->isSuperAdmin()) {
			return response()->json(['status' => 'error', 'reason' => trans('main.error.недостаточно-прав-доступа')]);
		}

		$productTypes = ProductType::where('is_active', true)
			->orderBy('sort')
			->get();

		$pilots = User::where('role', User::ROLE_PILOT)
			->orderBy('lastname')
			->orderBy('name')
			->get();

		$VIEW = view('admin.product.modal.add', [
			'productTypes' => $productTypes,
			'pilots' => $pilots,
		]);
		
		return response()->json(['status' => 'success', 'html' => (string)$VIEW]);
	}
	
	/**
	 * @param $id
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function confirm($id)
	{
		if (!$this->request->ajax()) {
			abort(404);
		}
		
		$user = Auth::user();
		
		if (!$user->isSuperAdmin()) {
			return response()->json(['status' => 'error', 'reason' => trans('main.error.недостаточно-прав-доступа')]);
		}

		$product = Product::find($id);
		if (!$product) return response()->json(['status' => 'error', 'reason' => trans('main.error.продукт-не-найден')]);
		
		$VIEW = view('admin.product.modal.delete', [
			'product' => $product,
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
		
		if (!$user->isSuperAdmin()) {
			return response()->json(['status' => 'error', 'reason' => trans('main.error.недостаточно-прав-доступа')]);
		}

		$rules = [
			'name' => 'required|max:255|unique:products,name',
			'alias' => 'required|max:255|unique:products,alias',
			'product_type_id' => 'required|numeric|min:0|not_in:0',
			'icon_file' => 'sometimes|image|max:512',
		];
		
		$validator = Validator::make($this->request->all(), $rules)
			->setAttributeNames([
				'name' => 'Name',
				'alias' => 'Alias',
				'product_type_id' => 'Product type',
				'icon_file' => 'Icon',
			]);
		if (!$validator->passes()) {
			return response()->json(['status' => 'error', 'reason' => $validator->errors()->all()]);
		}

		$isIconFileUploaded = false;
		if($iconFile = $this->request->file('icon_file')) {
			$isIconFileUploaded = $iconFile->move(public_path('upload/product/icon'), $iconFile->getClientOriginalName());
		}

		$product = new Product();
		$product->name = $this->request->name;
		$product->public_name = $this->request->public_name ?? '';
		$product->alias = $this->request->alias;
		$product->product_type_id = $this->request->product_type_id;
		$product->user_id = $this->request->user_id ?? 0;
		$product->duration = $this->request->duration ?? 0;
		$product->is_active = $this->request->is_active;
		$data = [];
		$data['description'] = $this->request->description ?? null;
		if ($isIconFileUploaded) {
			$data['icon_file_path'] = 'product/icon/' . $iconFile->getClientOriginalName();
		}
		$product->data_json = $data;
		if (!$product->save()) {
			return response()->json(['status' => 'error', 'reason' => trans('main.error.повторите-позже')]);
		}
		
		return response()->json(['status' => 'success', 'message' => 'Product was successfully created']);
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
		
		if (!$user->isSuperAdmin()) {
			return response()->json(['status' => 'error', 'reason' => trans('main.error.недостаточно-прав-доступа')]);
		}

		$product = Product::find($id);
		if (!$product) return response()->json(['status' => 'error', 'reason' => trans('main.error.продукт-не-найден')]);
		
		$rules = [
			'name' => 'required|max:255|unique:products,name,' . $id,
			'public_name' => 'required|max:255',
			'alias' => 'required|max:255|unique:products,alias,' . $id,
			'product_type_id' => 'required|numeric|min:0|not_in:0',
			'icon_file' => 'sometimes|image|max:512',
		];
		
		$validator = Validator::make($this->request->all(), $rules)
			->setAttributeNames([
				'name' => 'Name',
				'public_name' => 'Public name',
				'alias' => 'Alias',
				'product_type_id' => 'Product type',
				'icon_file' => 'Icon',
			]);
		if (!$validator->passes()) {
			return response()->json(['status' => 'error', 'reason' => $validator->errors()->all()]);
		}

		$isIconFileUploaded = false;
		if($iconFile = $this->request->file('icon_file')) {
			$isIconFileUploaded = $iconFile->move(public_path('upload/product/icon'), $iconFile->getClientOriginalName());
		}

		$product->name = $this->request->name;
		$product->public_name = $this->request->public_name;
		$product->alias = $this->request->alias;
		$product->product_type_id = $this->request->product_type_id;
		$product->user_id = $this->request->user_id ?? 0;
		$product->duration = $this->request->duration ?? 0;
		$product->is_active = $this->request->is_active;
		$data = is_array($product->data_json) ? $product->data_json : json_decode($product->data_json, true);
		$data['description'] = $this->request->description ?? null;
		if ($isIconFileUploaded) {
			$data['icon_file_path'] = 'product/icon/' . $iconFile->getClientOriginalName();
		}
		$product->data_json = $data;
		if (!$product->save()) {
			return response()->json(['status' => 'error', 'reason' => trans('main.error.повторите-позже')]);
		}
		
		return response()->json(['status' => 'success', 'message' => 'Product was successfully saved']);
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
		
		$user = Auth::user();
		
		if (!$user->isSuperAdmin()) {
			return response()->json(['status' => 'error', 'reason' => trans('main.error.недостаточно-прав-доступа')]);
		}

		$product = Product::find($id);
		if (!$product) return response()->json(['status' => 'error', 'reason' => trans('main.error.продукт-не-найден')]);
		
		if (!$product->delete()) {
			return response()->json(['status' => 'error', 'reason' => trans('main.error.повторите-позже')]);
		}
		
		return response()->json(['status' => 'success', 'message' => 'Product was successfully deleted']);
	}

	/**
	 * @param $id
	 *
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function deleteIcon($id)
	{
		if (!$this->request->ajax()) {
			abort(404);
		}
		
		$user = Auth::user();
		
		if (!$user->isSuperAdmin()) {
			return response()->json(['status' => 'error', 'reason' => trans('main.error.недостаточно-прав-доступа')]);
		}

		$product = Product::find($id);
		if (!$product) return response()->json(['status' => 'error', 'reason' => trans('main.error.продукт-не-найден')]);

		$data = $product->data_json;
		unset($data['icon_file_path']);
		$product->data_json = $data;
		if (!$product->save()) {
			return response()->json(['status' => 'error', 'reason' => trans('main.error.повторите-позже')]);
		}
		
		return response()->json(['status' => 'success', 'message' => 'Product icon was successfully deleted']);
	}
}
