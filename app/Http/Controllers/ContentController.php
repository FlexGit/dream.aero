<?php

namespace App\Http\Controllers;

use App\Models\Content;
use App\Services\HelpFunctions;
use Auth;
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

		$user = Auth::user();
		$city = $user->city;
		
		$parentContent = HelpFunctions::getEntityByAlias(Content::class, $type . '_' . $city->alias);
		if (!$parentContent) return response()->json(['status' => 'error', 'reason' => trans('main.error.некорректные-параметры')]);
	
		$id = $this->request->id ?? 0;

		$contents = Content::orderByDesc('id')
			->where('city_id', $city->id)
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
		
		$user = Auth::user();
		$city = $user->city;
		
		if (!$user->isSuperAdmin()) {
			return response()->json(['status' => 'error', 'reason' => trans('main.error.недостаточно-прав-доступа')]);
		}
		
		$parentContent = HelpFunctions::getEntityByAlias(Content::class, $type . '_' . $city->alias);
		if (!$parentContent) return response()->json(['status' => 'error', 'reason' => trans('main.error.некорректные-параметры')]);

		$content = Content::where('parent_id', $parentContent->id)
			->find($id);
		if (!$content) return response()->json(['status' => 'error', 'reason' => trans('main.error.материал-не-найден')]);

		$VIEW = view('admin.content.modal.edit', [
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
	public function add($type)
	{
		if (!$this->request->ajax()) {
			abort(404);
		}
		
		$user = Auth::user();
		$city = $user->city;
		
		if (!$user->isSuperAdmin()) {
			return response()->json(['status' => 'error', 'reason' => trans('main.error.недостаточно-прав-доступа')]);
		}
		
		$parentContent = HelpFunctions::getEntityByAlias(Content::class, $type . '_' . $city->alias);
		if (!$parentContent) return response()->json(['status' => 'error', 'reason' => trans('main.error.некорректные-параметры')]);

		$VIEW = view('admin.content.modal.add', [
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
	public function confirm($type, $id)
	{
		if (!$this->request->ajax()) {
			abort(404);
		}
		
		$user = Auth::user();
		$city = $user->city;
		
		if (!$user->isSuperAdmin()) {
			return response()->json(['status' => 'error', 'reason' => trans('main.error.недостаточно-прав-доступа')]);
		}

		$parentContent = HelpFunctions::getEntityByAlias(Content::class, $type . '_' . $city->alias);
		if (!$parentContent) return response()->json(['status' => 'error', 'reason' => trans('main.error.некорректные-параметры')]);

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
		
		$user = Auth::user();
		$city = $user->city;

		if ($type == Content::REVIEWS_TYPE) {
			$rules = [
				'title' => ['required', 'min:3', 'max:250'],
				'published_at' => ['date'],
			];
			
			$validator = Validator::make($this->request->all(), $rules)
				->setAttributeNames([
					'title' => 'Title',
					'published_at' => 'Publication date',
				]);
		} else {
			$rules = [
				'title' => ['required', 'min:3', 'max:250'],
				'alias' => ['required', 'min:3', 'max:250', 'regex:/([A-Za-z0-9\-]+)/'],
				'published_at' => ['date'],
				'photo_preview_file' => ['sometimes', 'image', 'max:20480', 'mimes:webp,png,jpg,jpeg'],
			];
			
			$validator = Validator::make($this->request->all(), $rules)
				->setAttributeNames([
					'title' => 'Title',
					'alias' => 'Alias',
					'published_at' => 'Publication date',
					'photo_preview_file' => 'Photo preview',
				]);
		}
		if (!$validator->passes()) {
			return response()->json(['status' => 'error', 'reason' => $validator->errors()->all()]);
		}

		$parentContent = HelpFunctions::getEntityByAlias(Content::class, $type . '_' . $city->alias);
		if (!$parentContent) {
			return response()->json(['status' => 'error', 'reason' => trans('main.error.некорректные-параметры')]);
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
		$content->alias = ($parentContent->alias == Content::PAGES_TYPE . '_' . $city->alias && $city) ? $this->request->alias . '_' . $city->alias : $this->request->alias;
		$content->preview_text = $this->request->preview_text;
		$content->detail_text = $this->request->detail_text;
		$content->parent_id = $parentContent->id;
		$content->city_id = $city->id;
		$content->meta_title = $this->request->meta_title;
		$content->meta_description = $this->request->meta_description;
		$content->is_active = (bool)$this->request->is_active;
		$content->data_json = $data;
		$content->published_at = $this->request->published_at;
		$content->published_end_at = $this->request->published_end_at ?? null;
		if (!$content->save()) {
			return response()->json(['status' => 'error', 'reason' => trans('main.error.повторите-позже')]);
		}
		
		return response()->json(['status' => 'success', 'message' => 'Content was successfully created']);
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
		
		$user = Auth::user();
		$city = $user->city;
		
		$parentContent = HelpFunctions::getEntityByAlias(Content::class, $type . '_' . $city->alias);
		if (!$parentContent) return response()->json(['status' => 'error', 'reason' => trans('main.error.некорректные-параметры')]);
		
		$content = Content::where('parent_id', $parentContent->id)
			->find($id);
		if (!$content) return response()->json(['status' => 'error', 'reason' => trans('main.error.материал-не-найден')]);

		$rules = [
			'title' => ['required', 'min:3', 'max:250'],
			'alias' => ['required', 'min:3', 'max:250', 'regex:/([A-Za-z0-9\-]+)/'],
			'published_at' => ['date'],
			'photo_preview_file' => ['sometimes', 'image', 'max:20480', 'mimes:webp,png,jpg,jpeg'],
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
		$content->city_id = $city->id;
		$content->meta_title = $this->request->meta_title;
		$content->meta_description = $this->request->meta_description;
		$content->is_active = (bool)$this->request->is_active;
		if ($data) {
			$content->data_json = $data;
		}
		$content->published_at = $this->request->published_at;
		$content->published_end_at = $this->request->published_end_at ?? null;
		if (!$content->save()) {
			return response()->json(['status' => 'error', 'reason' => trans('main.error.повторите-позже')]);
		}
		
		return response()->json(['status' => 'success', 'message' => 'Content was successfully saved']);
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
		
		$user = Auth::user();
		$city = $user->city;
		
		if (!$user->isSuperAdmin()) {
			return response()->json(['status' => 'error', 'reason' => trans('main.error.недостаточно-прав-доступа')]);
		}

		$parentContent = HelpFunctions::getEntityByAlias(Content::class, $type . '_' . $city->alias);
		if (!$parentContent) return response()->json(['status' => 'error', 'reason' => trans('main.error.некорректные-параметры')]);

		$content = Content::where('parent_id', $parentContent->id)
			->find($id);
		if (!$content) return response()->json(['status' => 'error', 'reason' => trans('main.error.материал-не-найден')]);

		if (!$content->delete()) {
			return response()->json(['status' => 'error', 'reason' => trans('main.error.повторите-позже')]);
		}
		
		return response()->json(['status' => 'success', 'message' => 'Content was successfully deleted']);
	}

	/**
	 * @param $type
	 *
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function imageUpload($type) {
		$user = Auth::user();
		$city = $user->city;
		
		$parentContent = HelpFunctions::getEntityByAlias(Content::class, $type . '_' . $city->alias);
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
