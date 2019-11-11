<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserAddressRequest;
use App\Models\UserAddress;
use Illuminate\Http\Request;

class UserAddressesController extends Controller
{
    public function index(Request $request)
    {
        return view('user_addresses.index',[
            'addresses' => $request->user()->addresses,
        ]);
    }

    public function create()
    {
        //由于新增页面和编辑页面比较类似，所以共用一个模板文件 create_and_edit
        return view('user_addresses.create_and_edit',[
            'address' => new UserAddress(),
        ]);
    }

    /**
     * 增加收货地址
     * 使用依赖注入方式将UserAddressRequest注入到$request中
     * Laravel 会自动调用 UserAddressRequest 中的 rules() 方法获取校验规则来对用户提交的数据进行校验，因此这里我们不需要手动调用 $this->validate() 方法。
     * @param UserAddressRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(UserAddressRequest $request)
    {
        /**
         * $request->user() 获取当前登录用户。
         * user()->addresses() 获取当前用户与地址的关联关系（注意：这里并不是获取当前用户的地址列表）
         * addresses()->create() 在关联关系里创建一个新的记录。
         * $request->only() 通过白名单的方式从用户提交的数据里获取我们所需要的数据。
         * return redirect()->route('user_addresses.index'); 跳转回我们的地址列表页面。
         */
        $request->user()->addresses()->create($request->only([
            'province',
            'city',
            'district',
            'address',
            'zip',
            'contact_name',
            'contact_phone',
        ]));
        return redirect()->route('user_addresses.index');
    }

    public function edit(UserAddress $user_address)
    {
        return view('user_addresses.create_and_edit',[
            'address' => $user_address
        ]);
    }

    public function update(UserAddress $user_address,UserAddressRequest $request)
    {
        $user_address->update($request->only([
            'province',
            'city',
            'district',
            'address',
            'zip',
            'contact_name',
            'contact_phone',
        ]));

        return redirect()->route('user_addresses.index');
    }

    public function destroy(UserAddress $user_address)
    {
        $user_address->delete();
        return [];
    }
}
