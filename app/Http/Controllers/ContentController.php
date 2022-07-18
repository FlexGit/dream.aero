<?php

namespace App\Http\Controllers;

use App\Models\City;
use App\Models\Content;
use App\Services\HelpFunctions;
use Illuminate\Http\Request;
use Validator;

class ContentController extends Controller
{
	private $request;
	
	/**
	 * @param Request $request
	 */
	public function __construct(Request $request) {
		$this->request = $request;
	}

	/**
	 * @param $type
	 *
	 * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
	 */
	public function index($type)
	{
		return view('admin.content.index', [
			'type' => $type,
		]);
	}

	/**
	 * @param $type
	 *
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function getListAjax($type)
	{
		if (!$this->request->ajax()) {
			abort(404);
		}

		$parentContent = HelpFunctions::getEntityByAlias(Content::class, $type);
		if (!$parentContent) {
			return response()->json(['status' => 'error', 'reason' => trans('main.error.некорректные-параметры')]);
		}
		
		$id = $this->request->id ?? 0;

		$contents = Content::orderByDesc('id')
			->where('parent_id', $parentContent->id);
		if ($this->request->search_content) {
			$contents = $contents->where(function ($query) {
				$query->where('title', 'like', '%' . $this->request->search_content . '%')
					->orWhere('detail_text', 'like', '%' . $this->request->search_content . '%')
					->orWhere('preview_text', 'like', '%' . $this->request->search_content . '%')
					->orWhere('alias', 'like', '%' . $this->request->search_content . '%')
					->orWhereHas('city', function ($q) {
						return $q->where('cities.name', 'like', '%' . $this->request->search_content . '%');
					})
				;
			});
		}
		if ($id) {
			$contents = $contents->where('id', '<', $id);
		}
		$contents = $contents->limit(20)->get();

		$VIEW = view('admin.content.list', [
			'contents' => $contents,
			'type' => $type,
		]);
		
		return response()->json(['status' => 'success', 'html' => (string)$VIEW]);
	}

	/**
	 * @param $type
	 * @param $id
	 *
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function edit($type, $id)
	{
		if (!$this->request->ajax()) {
			abort(404);
		}

		$parentContent = HelpFunctions::getEntityByAlias(Content::class, $type);
		if (!$parentContent) {
			return response()->json(['status' => 'error', 'reason' => trans('main.error.некорректные-параметры')]);
		}

		$content = Content::where('parent_id', $parentContent->id)
			->find($id);
		if (!$content) return response()->json(['status' => 'error', 'reason' => trans('main.error.материал-не-найден')]);

		$cities = City::orderBy('name')
			->get();

		$VIEW = view('admin.content.modal.edit', [
			'content' => $content,
			'type' => $type,
			'cities' => $cities,
		]);
		
		return response()->json(['status' => 'success', 'html' => (string)$VIEW]);
	}

	/**
	 * @param $type
	 *
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function add($type)
	{
		if (!$this->request->ajax()) {
			abort(404);
		}

		$parentContent = HelpFunctions::getEntityByAlias(Content::class, $type);
		if (!$parentContent) {
			return response()->json(['status' => 'error', 'reason' => trans('main.error.некорректные-параметры')]);
		}

		$cities = City::orderBy('name')
			->get();

		$VIEW = view('admin.content.modal.add', [
			'type' => $type,
			'cities' => $cities,
		]);
		
		return response()->json(['status' => 'success', 'html' => (string)$VIEW]);
	}

	/**
	 * @param $type
	 * @param $id
	 *
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function confirm($type, $id)
	{
		if (!$this->request->ajax()) {
			abort(404);
		}

		if (!$this->request->user()->isSuperAdmin()) {
			return response()->json(['status' => 'error', 'reason' => trans('main.error.недостаточно-прав-доступа')]);
		}

		$parentContent = HelpFunctions::getEntityByAlias(Content::class, $type);
		if (!$parentContent) {
			return response()->json(['status' => 'error', 'reason' => trans('main.error.некорректные-параметры')]);
		}

		$content = Content::where('parent_id', $parentContent->id)
			->find($id);
		if (!$content) return response()->json(['status' => 'error', 'reason' => trans('main.error.материал-не-найден')]);

		$VIEW = view('admin.content.modal.delete', [
			'content' => $content,
			'type' => $type,
		]);

		return response()->json(['status' => 'success', 'html' => (string)$VIEW]);
	}

	/**
	 * @param $type
	 *
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function store($type)
	{
		if (!$this->request->ajax()) {
			abort(404);
		}

		$rules = [
			'title' => ['required', 'min:3', 'max:250'],
			'alias' => ['required', 'min:3', 'max:250', 'regex:/([A-Za-z0-9\-]+)/', 'unique:contents'],
			'published_at' => ['date'],
			'photo_preview_file' => ['sometimes', 'image', 'max:5120', 'mimes:webp,png,jpg,jpeg'],
		];
		
		$validator = Validator::make($this->request->all(), $rules)
			->setAttributeNames([
				'title' => 'Title',
				'alias' => 'Alias',
				'published_at' => 'Publication date',
				'photo_preview_file' => 'Photo preview',
			]);
		if (!$validator->passes()) {
			return response()->json(['status' => 'error', 'reason' => $validator->errors()->all()]);
		}

		$parentContent = HelpFunctions::getEntityByAlias(Content::class, $type);
		if (!$parentContent) {
			return response()->json(['status' => 'error', 'reason' => trans('main.error.некорректные-параметры')]);
		}
		
		$cityId = $this->request->city_id ?? 0;
		if ($parentContent->alias == Content::PAGES_TYPE) {
			$city = City::find($cityId);
		}

		$data = [];
		if($file = $this->request->file('photo_preview_file')) {
			$isFileUploaded = $file->move(public_path('upload/content/' . $type), $file->getClientOriginalName());
			if ($isFileUploaded) {
				$data['photo_preview_file_path'] = $isFileUploaded ? 'content/' . $type . '/' . $file->getClientOriginalName() : '';
			}
		}
		
		$videoUrl = $this->request->video_url ?? '';
		if ($videoUrl) {
			$data['video_url'] = $videoUrl;
		}
		
		$content = new Content();
		$content->title = $this->request->title;
		$content->alias = ($parentContent->alias == Content::PAGES_TYPE && $city) ? $this->request->alias . '_' . $city->alias : $this->request->alias;
		$content->preview_text = $this->request->preview_text;
		$content->detail_text = $this->request->detail_text;
		$content->parent_id = $parentContent->id;
		$content->city_id = $cityId;
		$content->meta_title = $this->request->meta_title;
		$content->meta_description = $this->request->meta_description;
		$content->meta_title_en = $this->request->meta_title_en;
		$content->meta_description_en = $this->request->meta_description_en;
		$content->is_active = (bool)$this->request->is_active;
		$content->data_json = $data;
		$content->published_at = $this->request->published_at;
		if (!$content->save()) {
			return response()->json(['status' => 'error', 'reason' => trans('main.error.повторите-позже')]);
		}
		
		return response()->json(['status' => 'success']);
	}

	/**
	 * @param $type
	 * @param $id
	 *
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function update($type, $id)
	{
		if (!$this->request->ajax()) {
			abort(404);
		}

		$parentContent = HelpFunctions::getEntityByAlias(Content::class, $type);
		if (!$parentContent) {
			return response()->json(['status' => 'error', 'reason' => trans('main.error.некорректные-параметры')]);
		}
		
		$cityId = $this->request->city_id ?? 0;

		$content = Content::where('parent_id', $parentContent->id)
			->find($id);
		if (!$content) return response()->json(['status' => 'error', 'reason' => trans('main.error.материал-не-найден')]);

		$rules = [
			'title' => ['required', 'min:3', 'max:250'],
			'alias' => ['required', 'min:3', 'max:250', 'regex:/([A-Za-z0-9\-]+)/', 'unique:contents,alias,' . $id],
			'published_at' => ['date'],
			'photo_preview_file' => ['sometimes', 'image', 'max:5120', 'mimes:webp,png,jpg,jpeg'],
		];

		$validator = Validator::make($this->request->all(), $rules)
			->setAttributeNames([
				'title' => 'Title',
				'alias' => 'Alias',
				'published_at' => 'Publication date',
				'photo_preview_file' => 'Photo preview',
			]);
		if (!$validator->passes()) {
			return response()->json(['status' => 'error', 'reason' => $validator->errors()->all()]);
		}
		
		$data = [];
		if($file = $this->request->file('photo_preview_file')) {
			$isFileUploaded = $file->move(public_path('upload/content/' . $type), $file->getClientOriginalName());
			if ($isFileUploaded) {
				$data['photo_preview_file_path'] = $isFileUploaded ? 'content/' . $type . '/' . $file->getClientOriginalName() : '';
			}
		}
		
		$videoUrl = $this->request->video_url ?? '';
		if ($videoUrl) {
			$data['video_url'] = $videoUrl;
		}
		
		$content->title = $this->request->title;
		$content->alias = $this->request->alias;
		$content->preview_text = $this->request->preview_text;
		$content->detail_text = $this->request->detail_text;
		$content->parent_id = $parentContent->id;
		$content->city_id = $cityId;
		$content->meta_title = $this->request->meta_title;
		$content->meta_description = $this->request->meta_description;
		$content->meta_title_en = $this->request->meta_title_en;
		$content->meta_description_en = $this->request->meta_description_en;
		$content->is_active = (bool)$this->request->is_active;
		if ($data) {
			$content->data_json = $data;
		}
		$content->published_at = $this->request->published_at;
		if (!$content->save()) {
			return response()->json(['status' => 'error', 'reason' => trans('main.error.повторите-позже')]);
		}
		
		return response()->json(['status' => 'success']);
	}

	/**
	 * @param $type
	 * @param $id
	 *
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function delete($type, $id)
	{
		if (!$this->request->ajax()) {
			abort(404);
		}

		if (!$this->request->user()->isSuperAdmin()) {
			return response()->json(['status' => 'error', 'reason' => trans('main.error.недостаточно-прав-доступа')]);
		}

		$parentContent = HelpFunctions::getEntityByAlias(Content::class, $type);
		if (!$parentContent) {
			return response()->json(['status' => 'error', 'reason' => trans('main.error.некорректные-параметры')]);
		}

		$content = Content::where('parent_id', $parentContent->id)
			->find($id);
		if (!$content) return response()->json(['status' => 'error', 'reason' => trans('main.error.материал-не-найден')]);

		if (!$content->delete()) {
			return response()->json(['status' => 'error', 'reason' => trans('main.error.повторите-позже')]);
		}

		return response()->json(['status' => 'success']);
	}

	/**
	 * @param $type
	 *
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function imageUpload($type) {
		$parentContent = HelpFunctions::getEntityByAlias(Content::class, $type);
		if (!$parentContent) {
			return response()->json(['status' => 'error', 'reason' => trans('main.error.некорректные-параметры')]);
		}

		$file = $this->request->file('file');
		if (!$file->move(public_path('/upload/content/' . $type . '/'), $file->getClientOriginalName())) {
			return response()->json(['status' => 'error', 'reason' => trans('main.error.не-удалось-загрузить-файл')]);
		}

		return response()->json([
			'location' => url('/upload/content/' . $type . '/' . $file->getClientOriginalName()),
		]);
	}
}
