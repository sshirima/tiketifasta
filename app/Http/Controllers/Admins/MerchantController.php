<?php
/**
 * Created by PhpStorm.
 * User: samson
 * Date: 3/12/2018
 * Time: 5:57 PM
 */

namespace App\Http\Controllers\Admins;


use App\Admin\MerchantAccountManager;
use App\Http\Requests\Merchant\CreateMerchantRequest;
use App\Repositories\Merchant\MerchantRepository;
use App\Repositories\Merchant\StaffRepository;
use Illuminate\Http\Request;
use Laracasts\Flash\Flash;
use Prettus\Repository\Criteria\RequestCriteria;

class MerchantController extends BaseController
{
    private $merchantRepository;
    private $staffRepository;

    public function __construct(MerchantRepository $merchantRepo, StaffRepository $staffRepository)
    {
        $this->middleware('auth:admin');
        $this->merchantRepository = $merchantRepo;
        $this->staffRepository = $staffRepository;
    }

    public function index(Request $request){

        $this->merchantRepository->pushCriteria(new RequestCriteria($request));

        return view('admins.pages.merchants.index')->with(['admin'=>\auth()->user(), 'merchants'=>$this->merchantRepository->all()]);
    }

    public function create(){
        return view('admins.pages.merchants.create')->with(['admin'=>\auth()->user()]);
    }

    public function show($id){
        $merchant = $this->merchantRepository->findWithoutFail($id);

        if (empty($merchant)) {
            Flash::error(__('page_merchant.error_not_found'));

            return redirect(route('admin.merchant.index'));
        }

        return view('admins.pages.merchants.show')->with(['admin'=>\auth()->user(),'merchant'=>$merchant]);
    }

    public function store(CreateMerchantRequest $request){

        $merchantManager = new MerchantAccountManager($this->merchantRepository, $this->staffRepository);

        if (!$merchantManager->createNewAccount( $request->all())){
            Flash::error(__('page_merchant.error_not_savedr'));
        }

        Flash::success(__('page_merchant.success_saved'));

        return redirect(route('admin.merchant.index'));
    }

    public function delete($id){
        $merchant = $this->merchantRepository->findWithoutFail($id);
        return view('admins.pages.merchants.delete')->with(['admin'=>\auth()->user(), 'merchant'=>$merchant]);
    }

    public function remove($id){

        $merchant = $this->merchantRepository->findWithoutFail($id);

        if (empty($merchant)) {
            Flash::error(__('page_merchant.error_not_found'));

            return redirect(route('admin.merchant.index'));
        }

        $this->merchantRepository->delete($id);

        Flash::success(__('page_merchant.success_deleted'));

        return redirect(route('admin.merchant.index'));
    }


}