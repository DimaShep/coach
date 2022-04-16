<?php namespace Shep\Coach\Http\Controllers;

//use App\Http\Controllers\Controller;
//use Illuminate\Http\Request;
use Illuminate\Http\Request;
use Shep\Coach\Facades\Coach;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Config;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Response;
//use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\URL;
use Shep\Coach\Models\CoachModel;
use TCG\Voyager\Facades\Voyager;

/**
 * The CoachController class.
 *
 * @package Shep\Coach
 * @author  Dmitriy <dmitriy.shepelenko@gmail.com>
 */
class CoachBaseController extends Controller
{
    protected $slug;
    protected $dataType;
    protected $model;
    protected $back;

    public function __construct(Request $request)
    {
        if($request->route()) {
            $this->slug = explode('.', $request->route()->getName())[1];

            $this->dataType = Coach::model('DataType')->where('slug', '=', $this->slug)->first();

            $this->model = (strlen($this->dataType->model) != 0) ? new $this->dataType->model() : false;
        }
        $this->back = null;
    }

    public function setBack($route_name)
    {
        $this->back = $route_name;
    }

    public function slug()
    {
        return $this->slug;
    }

    public function model()
    {
        return $this->model;
    }

    public function dataType()
    {
        return $this->dataType;
    }


    public function updateOrCreate(Request $request, $id = 0)
    {
        $columns = $this->model->getColumns();
        foreach ($request->all() as $name => $val)
        {
            if(!$columns[$name])
                continue;
            if($columns[$name] == 'boolean')
                $data[$name] = $val=='on'?true:false;
            else
                $data[$name] = $val;
        }

        foreach ($columns as $column => $type)
        {
            if(!$data[$column] && $type=='boolean')
                $data[$column] = false;
        }

        if($id) {
            $ret = $this->model->find($id);
            $ret->update($data);
        }
        else
            $ret = $this->model->create($data);

        return $ret;
    }

    public function show(Request $request, $id)
    {
        $data='';
        return Coach::view(null, $this, 'show', compact('data'));
    }

    public function index(Request $request)
    {
        $data =  $this->model->all();
        return Coach::view(null, $this, 'browse', compact('data'));
    }

    public function create(Request $request)
    {
        return $this->edit($request, 0);
    }


    public function edit(Request $request, $id)
    {
        $data = null;
        if($id)
            $data = $this->model->find($id);

        return Coach::view(null, $this, 'edit-add', compact('data',));
    }

    public function store(Request $request)
    {
        return $this->update($request, 0);
    }

    public function update(Request $request, $id)
    {
        $ret = $this->updateOrCreate($request, $id);

        return $this->returnUpdate($request, $id, $ret);
    }

    public function returnUpdate(Request $request, $id, $ret)
    {
        if ($request->ajax()) {
            return response()->json(['success' => true, 'data' => $ret]);
        }
        if($this->back)
            $ret = redirect($this->back);
        else
            $ret = redirect()->route("coach.{$this->slug}.index");

        $ret->with([
                    'message'    => $id?__('coach::message.successfully_added_new'):__('coach::message.successfully_updated')." {$this->dataType->name}",
                    'alert-type' => 'success',
                ]);
        return $ret;
    }

    public function destroy(Request $request, $id)
    {
        $ids = [];
        if (empty($id)) {
            // Bulk delete, get IDs from POST
            $ids = explode(',', $request->ids);
        } else {
            // Single item delete, get ID from URL
            $ids[] = $id;
        }
        foreach ($ids as $id) {
            $data = call_user_func([$this->model, 'findOrFail'], $id);
        }

        $displayName = count($ids) > 1 ? $this->dataType->name_plural : $this->dataType->name;

        $res = $data->destroy($ids);
        $data = $res
            ? [
                'message'    => __('coach::message.successfully_deleted')." {$displayName}",
                'alert-type' => 'success',
            ]
            : [
                'message'    => __('coach::message.error_deleting')." {$displayName}",
                'alert-type' => 'error',
            ];

        if ($request->ajax()) {
            return response()->json(['success' => true, 'data' => $data]);
        }

        return redirect()->route("coach.{$this->slug}.index")->with($data);
    }
}
