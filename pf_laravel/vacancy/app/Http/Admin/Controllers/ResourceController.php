<?php


namespace App\Http\Admin\Controllers;


use App\Base\Interfaces\ManageServiceInterface;
use App\Base\Interfaces\DataProviderInterface;
use DB;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Str;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

abstract class ResourceController extends Controller
{
    protected bool $supportsShowMethod = false;

    public function __construct(
        protected DataProviderInterface $repository,
        protected ManageServiceInterface  $service,
    )
    {
    }

    public function index(): View
    {
        return $this->render('index', [
            'dataProvider' => $this->repository->toEloquentDataProvider(),
        ]);
    }

    public function create(): View
    {
        return $this->render('create', model: $this->createModel());
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validateRequest($request);

        if ($model = DB::transaction(fn() => $this->service->create($data))) {
            $request->session()->flash('success', __('admin.messages.crud.stored'));
            return $this->redirect($model);
        }

        $request->session()->flash('error', __('admin.messages.crud.error'));
        return $this->redirect();
    }

    public function show(Request $request): View
    {
        return $this->render('show', model: $this->findModel($request));
    }

    public function edit(Request $request): View
    {
        return $this->render('update', model: $this->findModel($request));
    }

    public function update(Request $request): RedirectResponse
    {
        $model = $this->findModel($request);
        $data = $this->validateRequest($request, $model);

        if ($model = DB::transaction(fn() => $this->service->update($model, $data))) {
            $request->session()->flash('success', __('admin.messages.crud.updated'));
            return $this->redirect($model);
        }

        $request->session()->flash('error', __('admin.messages.crud.error'));
        return $this->redirect();
    }

    public function destroy(Request $request): RedirectResponse
    {
        $model = $this->findModel($request);

        if (DB::transaction(fn() => $this->service->destroy($model))) {
            $request->session()->flash('success', __('admin.messages.crud.destroyed'));
            return $this->redirect($model);
        }

        $request->session()->flash('error', __('admin.messages.crud.error'));
        return $this->redirect();
    }

    abstract protected function resourceClass(): string;

    abstract protected function rules($model = null): array;

    protected function resourceName(): string
    {
        return lcfirst(class_basename($this->resourceClass()));
    }

    protected function customAttributes(): array
    {
        return collect($this->rules())
            ->map(fn($value, $field) => __("admin.columns.$field"))
            ->toArray();
    }

    protected function viewParameters(): array
    {
        return [];
    }

    private function findModel(Request $request): Model|string|null
    {
        $resourceName = Str::singular($this->resourceName());

        return $this->repository->one($request->route($resourceName)) ?: throw new NotFoundHttpException();
    }

    private function createModel(): Model|string|null
    {
        return new ($this->resourceClass());
    }

    private function render(string $view, array $parameters = [], Model $model = null): View
    {
        $resourceNameSingular = Str::singular($this->resourceName());
        $resourceNamePlural = Str::plural($this->resourceName());
        $parameters = array_merge($this->viewParameters(), $parameters, $model ? [$resourceNameSingular => $model]: []);
        return view("admin.$resourceNamePlural.$view", $parameters);
    }

    private function redirect(Model $model = null, array $parameters = []): RedirectResponse
    {
        $resourceNamePlural = Str::plural($this->resourceName());
        $resourceNameSingular = Str::singular($this->resourceName());

        $route = $model && $this->supportsShowMethod ? 'show' : 'index';

        if ($route === 'show') {
            $parameters[$resourceNameSingular] = $model;
        }

        return redirect(route("admin.$resourceNamePlural.$route", $parameters));
    }

    public function validateRequest(Request $request, Model $model = null): array
    {
        return parent::validate($request, $this->rules($model), [], $this->customAttributes());
    }
}
