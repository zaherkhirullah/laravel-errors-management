<?php

namespace Hayrullah\ErrorManagement\Http\Controllers;

use App\Http\Controllers\Controller;
use Exception;
use Hayrullah\ErrorManagement\Models\RecordError;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Yajra\DataTables\DataTables;

class RecordErrorController extends Controller
{
//    protected $permissionName = "error-records";
    protected $permissionName = '';

    /**
     * RecordErrorController constructor.
     */
    public function __construct()
    {
    }

    /**
     * RecordErrorController constructor.
     */
    public function dashboard()
    {
        return view('ErrorManagement::dashboard');
    }

    /**
     * @param Request $request
     * @param $code
     * @param null $trash
     *
     * @return Factory|View
     * @throws Exception
     *
     */
    public function index(Request $request, $code, $trash = null)
    {
        if (!check_user_authorize($this->permissionName, $trash)) {
            return view('ErrorManagement::errors.401');
        }
        $user_can_delete = is_can_delete($this->permissionName);
        $user_can_restore = is_can_restore($this->permissionName);
        $user_can_force_delete = is_can_force_delete($this->permissionName);
        if (!$this->allowed_code($code)) {
            abort(404);
        }
        if ($request->ajax()) {
            $rows = RecordError::Type($code)->with('visits')->withCount('visits');
            $rows = $trash ? $rows->onlyTrashed()->get() : $rows->get();

            $datatable = Datatables::of($rows);
            $datatable->addColumn('link', function ($row) {
                $link = Str::limit($row->link, 60);

                return "<p><a href='{$row->link}'  target='_blank' data-toggle='tooltip' title='$row->link' style='word-break: break-all'>$link</a></p>";
            });
            $datatable->addColumn('previous', function ($row) {
                $previous = Str::limit($row->previous, 60);

                return "<p><a href='{$row->previous}'  target='_blank' data-toggle='tooltip' title='$row->previous' style='word-break: break-all'>$previous</a></p>";
            });
            $datatable->addColumn('visits', function ($row) {
                return "<p class='text-center align-middle'><span class='btn btn-sm btn-sm badge-warning'> <i class='fas fa-eye'></i> $row->visits_count  </span></p>";
            });
            $datatable->addColumn('last_visit', function ($row) {
                $last_visit = $row->visits()->latest()->first();
                $last_visit = optional($last_visit)->created_at;

                return "<p class='text-center align-middle'><span> $last_visit </span></p>";
            });
            $datatable->addColumn('action', function ($row) use ($code, $user_can_delete, $user_can_restore, $user_can_force_delete, $trash) {
                $url = url("$row->bPrefixPath/$code/$row->id");
                if ($trash) {
                    $output = '';
                    if ($user_can_restore) {
                        $title = __('backend.restore');
                        $output .= "<a href='#' class='btn btn-sm btn-info btn-block btn-restore' id='$row->id'><i class='fas fa-trash-restore'></i> $title  </a>";
                    }
                    if ($user_can_force_delete) {
                        $title = __('backend.force_delete');
                        $output .= "<a href='#' class='btn btn-sm btn-warning btn-block btn-force-delete' id='$row->id'><i class='fas fa-fire-alt'></i>  $title </a>";
                    }

                    return $output;
                }
                $title = __('backend.report');
                $output = "<a href='$url' class='btn btn-xs btn-info btn-block' id='$row->id'><i class='fas fa-eye'></i> $title </a>";
                if ($user_can_delete) {
                    $title = __('backend.delete');
                    $output .= "<a href='#' class='btn btn-xs btn-danger btn-block delete' id='{$row->id}'><i class='fas fa-trash'></i> $title </a>";
                }

                return $output;
            });
            $datatable->rawColumns([
                'link' => 'link',
                'previous' => 'previous',
                'visits' => 'visits',
                'last_visit' => 'last_visit',
                'action' => 'action',
            ]);

            return $datatable->make(true);
        }

        return view('ErrorManagement::index', compact('code'));
    }

    public function show(Request $request, $code, $id)
    {
        if (!check_user_authorize($this->permissionName)) {
            return view('RecordErrors::errors.401');
        }

        $user_can_delete = is_can_delete($this->permissionName);

        $record = RecordError::findOrFail($id);
        if ($request->ajax()) {
//            $rows = RecordError::where('link', $row->link)->where('id', '!=', $id);
            $rows = $record->visits;

            return Datatables::of($rows)
                ->addColumn('link', function ($row) use ($record) {
                    $link = Str::limit($record->link, 60);

                    return "<p><a href='{$record->link}'  target='_blank' data-toggle='tooltip' title='$record->link' style='word-break: break-all'>$link</a></p>";
                })->addColumn('previous', function ($row) {
                    $previous = Str::limit($row->previous, 60);

                    return "<p><a href='{$row->previous}'  target='_blank' data-toggle='tooltip' title='$row->previous' style='word-break: break-all'>$previous</a></p>";
                })
                ->addColumn('created_at', function ($row) {
                    return "<p class='text-center align-middle'><span>$row->created_at
                </span></p>";
                })
                ->addColumn('action', function ($row) use ($user_can_delete) {
                    $output = '';
                    if ($user_can_delete) {
                        $title = __('backend.delete');
                        $output .= "<a href='#' class='btn btn-xs btn-danger btn-block delete' id='$row->id'><i class='fas fa-trash'></i> $title</a>";
                    }

                    return $output;
                })
                ->rawColumns([
                    'link' => 'link',
                    'previous' => 'previous',
                    'created_at' => 'created_at',
                    'action' => 'action',
                ])
                ->make(true);
        }
        $row = $record;

        return view('ErrorManagement::show', compact('row', 'code'));
    }

    /**
     * @param Request $request
     *
     * @return bool|JsonResponse
     */
    public function store(Request $request, $code)
    {
        if (!$this->allowed_code($code)) {
            abort(404);
        }
        if (!$request->ajax()) {
            return false;
        }
        $validation = Validator::make($request->all(), [
            'link' => 'required',
            'previous' => 'required',
        ]);
        if ($validation->fails()) {
            $errors = jsonOutput(getArrayValidationErrors($validation));

            return response()->json($errors, 200);
        }
        if ($request->link !== $request->previous) {
            $error = RecordError::where('link', $request->link)->first();
            if (!$error) {
                $error = new RecordError();
                $error->code = $code;
                $error->link = $request->link;
                $error->previous = $request->previous;
                $error->save();
            }
            $error->createVisit($request);
        }

        return response()->json(jsonOutput([], 'success'), 200);
    }

    /**
     * get full src name.
     *
     * @param $full_src
     *
     * @return string
     */
    private function getFullSrc($full_src)
    {
        if (strpos($full_src, 'gclid=') !== false) {
            $src = 'Google AdWords';
        } elseif (strpos($full_src, 'fbclid=') !== false) {
            $src = 'Facebook';
        } elseif (strpos($full_src, '?hyrsrc=web-push') !== false) {
            $src = 'Web Notification';
        } elseif (strpos($full_src, '?hyrsrc=mail-campaigns') !== false) {
            $src = 'Email Campaigns';
        } elseif (strpos($full_src, '?hyrsrc=ads-banner') !== false) {
            $src = 'Advertising banners';
        } else {
            $src = $this->src;
        }

        return $src;
    }

    /**
     * @param Request $request
     * @param $id
     *
     * @return JsonResponse
     */
    public function destroy(Request $request, $id)
    {
        $row = RecordError::find($id);
        if (!$row) {
            return response()->json('The item not found', 404);
        }
        if (!$request->withItems) {
            // get all rows in the same link and delete all of theme
            return delete_record($row, $this->permissionName);
        }
        if ($request->withItems == true) {
            RecordError::where('link', $row->link)->delete();
        }

        return response()->json('The items has been "deleted" successfully', 200);
    }

    /**
     * @param Request $request
     * @param $id
     *
     * @return JsonResponse
     */
    public function restore(Request $request, $id)
    {
        $row = RecordError::withTrashed()->find($id);
        if (!$row) {
            return response()->json('The item not found', 404);
        }
        if (!$request->withItems) {
            // get all rows in the same link and delete all of theme
            return restore_record($row, $this->permissionName);
        }
        if ($request->withItems == true) {
            RecordError::onlyTrashed()->where('link', $row->link)->restore();
        }

        return response()->json('The items has been "restored" successfully', 200);
    }

    /**
     * @param Request $request
     * @param $id
     *
     * @return JsonResponse
     */
    public function forceDelete(Request $request, $id)
    {
        $row = RecordError::withTrashed()->find($id);
        if (!$row) {
            return response()->json('The item not found', 404);
        }
        if (!$request->withItems) {
            // get all rows in the same link and delete all of theme
            return force_delete_record($row, $this->permissionName);
        }
        if ($request->withItems == true) {
            RecordError::onlyTrashed()->where('link', $row->link)->forceDelete();
        }

        return response()->json('The items has been "force Deleted" successfully', 200);
    }

    /**
     * @param $code
     *
     * @return bool
     */
    private function allowed_code($code)
    {
        switch ($code) {
            case '401':
            case '403':
            case '404':
            case '419':
            case '429':
            case '500':
            case '503':
                return true;
            default:
                return false;
        }
    }
}
