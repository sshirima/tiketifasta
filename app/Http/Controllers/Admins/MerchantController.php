<?php
/**
 * Created by PhpStorm.
 * User: samson
 * Date: 3/12/2018
 * Time: 5:57 PM
 */

namespace App\Http\Controllers\Admins;


use App\Http\Requests\Admin\UpdateMerchantRequest;
use App\Http\Requests\Merchant\CreateMerchantRequest;
use App\Repositories\Merchant\MerchantRepository;
use App\Repositories\Merchant\StaffRepository;
use App\Services\Merchants\AuthorizeMerchantAccount;
use App\Services\Merchants\CheckMerchantContractStatus;
use App\Services\Merchants\MerchantManager;
use Illuminate\Http\Request;
use Laracasts\Flash\Flash;
use Prettus\Repository\Criteria\RequestCriteria;

class MerchantController extends BaseController
{
    use CheckMerchantContractStatus, AuthorizeMerchantAccount;

    private $merchantRepository;
    private $staffRepository;

    public function __construct(MerchantRepository $merchantRepo, StaffRepository $staffRepository)
    {
        parent::__construct();
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

    public function store(CreateMerchantRequest $request){

        $input =  $request->all();

        $merchantManager = new MerchantManager();

        $merchant = $merchantManager->addMerchant( $input);

        if (!isset($merchant)){
            Flash::error(__('page_merchant.error_not_saved'));
        }

        Flash::success(__('page_merchant.success_saved'));

        return redirect(route('admin.merchant.index'));
    }

    public function edit($merchantId){
        return view('admins.pages.merchants.edit')->with(['admin'=>\auth()->user(),'merchant'=>$this->merchantRepository->findWithoutFail($merchantId)]);
    }

    public function update(UpdateMerchantRequest $request, $merchantId){

        $merchant = $this->merchantRepository->findWithoutFail($merchantId);

        if (empty($merchant)) {
            Flash::error(__('page_merchant.error_not_found'));

            return redirect(route('admin.merchant.index'));
        }

        $this->merchantRepository->update($request->all(), $merchantId);

        Flash::success('Merchants account has been updated');

        return redirect(route('admin.merchant.index'));
    }

    public function show($id){

        $merchant = $this->merchantRepository->findWithoutFail($id);

        if (empty($merchant)) {
            Flash::error(__('page_merchant.error_not_found'));

            return redirect(route('admin.merchant.index'));
        }

        return view('admins.pages.merchants.show')->with(['admin'=>\auth()->user(),'merchant'=>$merchant]);
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

    public function authorizeMerchant(Request $request, $id){
        $merchant= $this->merchantRepository->findWithoutFail($id);
        $merchant = $this->getMerchantContractStatus($merchant);
        return view('admins.pages.merchants.authorize')->with(['merchant'=>$merchant]);
    }

    public function enableMerchant(Request $request, $id){
        $merchant= $this->merchantRepository->findWithoutFail($id);
        $this->enableMerchantAccount($merchant);
        return redirect(route('admin.merchant.index'));
    }

    public function disableMerchant(Request $request, $id){
        $merchant= $this->merchantRepository->findWithoutFail($id);
        $this->disableMerchantAccount($merchant);
        return redirect(route('admin.merchant.index'));
    }
}