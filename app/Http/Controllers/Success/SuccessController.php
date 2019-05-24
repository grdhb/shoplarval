<?php

namespace App\Http\Controllers\Success;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;

class SuccessController extends Controller
{

    public function swInfo(Request $request)
    {
        if ($request->goods_id == '') {
            return ['code' => 2, 'msg' => '请选择一个商品'];
            die;
        }
        DB::beginTransaction();
        try {
            $this->tjdt($request);
            $res = $request->all();
            DB::commit();
            return ['code' => 1, 'msg' => '提交成功'];
        } catch (\Exception $e) {
            DB::rollBack();
            echo $e->getMessage();
        }
    }
    //提交订单执行
    public function tjdt(Request $request)
    {
        //用户id
        $id = session('user');
        //接收商品id和地址id
        $post = $request->all();
        // dd($post);
        //吧商品id
        $goods_id = explode(',', $post['goods_id']);

        //支付状态
        $order_status = 1;
        //收货地址id
        $address_id = $post['address_id'];
        //获取订单号，吧用户id传递过去
        $order_no = $this->careateOrder($id);
        //获取总金额
        $order_amount = $this->getOrderAmount($goods_id, $id);
        //往订单表添加(order)
        $oderInfo['order_no'] = $order_no; //订单号
        $oderInfo['id'] = $id; //用户id
        $oderInfo['order_amount'] = $order_amount; //总价格
        $oderInfo['order_status'] = $order_status; //支付状态
        $oderInfo['create_time'] = time(); //添加时间
        $oderInfo['update_time'] = time(); //修改时间
        //往订单表详情表添加数据(),返回最后一个添加的id
        $order_id = DB::table('order')->insertGetId($oderInfo);
        //吧订单ID添入session。在支付的时候获取
        session(['order_id' => $order_id]);
        //先去商品表()根据商品id和用户id查询商品数据
        $goodsInfo = $this->getOrderDetail($goods_id, $id);
        $goodsInfo = json_decode(json_encode($goodsInfo), true);
        foreach ($goodsInfo as $k => $v) {
            $goodsInfo[$k]['order_id'] = $order_id;
            $goodsInfo[$k]['user_id'] = $id;
            $goodsInfo[$k]['create_time'] = time();
            $goodsInfo[$k]['update_time'] = time();
        }
        // dump($goodsInfo);
        //查到商品就往订单详情表添加（order_detail）//因为上面查找的是二维数组所以加上下标
        $detailInfo = DB::table('order_detail')->insert($goodsInfo);

        /** 订单详情添加成功
         * 先去收货地址表查询是否有这个收货地址
         * 有就往订单收获地址表添加数据（order_address）
         */
        $where1 = [
            'id' => $id,
            'address_id' => $address_id,
        ];
        //去地址表查询是否有这个数据
        $addressInfo = DB::table('address')->where($where1)->first();
        $addressInfo = json_decode(json_encode($addressInfo), true);
        if (!$addressInfo) {
            echo ('没有此收货地址，请重新选择');
            exit();
        }
        $addressInfo['order_id'] = $order_id;
        $addressInfo['user_id'] = $id;
        $addressInfo['create_time'] = time();
        $addressInfo['update_time'] = time();
        unset($addressInfo['address_id']);
        unset($addressInfo['is_defaut']);
        unset($addressInfo['address_mail']);
        unset($addressInfo['id']);
        $res2 = DB::table('order_address')->insert($addressInfo);

        //减少库存（商品表goods）
        $res3 = DB::table('goods as g')
            ->select('g.goods_number', 'c.buy_number', 'c.goods_id')
            ->join('cart as c', 'g.goods_id', '=', 'c.goods_id')
            ->get();
        foreach ($goods_id as $k => $v) {
            foreach ($res3 as $kk => $vv) {
                if ($v == $vv->goods_id) {
                    $vv->goods_number = $vv->goods_number - $vv->buy_number;
                    $res4 = DB::table('goods')->where('goods_id', $v)->update(['goods_number' => $vv->goods_number]);
                }
            }
        }
        //删除购物车数据（cart）
        foreach ($goods_id as $k => $v) {
            $where5 = [
                'id' => $id,
                'goods_id' => $v
            ];
            $res4 = DB::table('cart')->where($where5)->delete();
        }
    }
    //订单号
    public function careateOrder($id)
    {
        return "HTT" . rand(10000, 99999) . $id;
    }
    //总金额
    public function getOrderAmount($goods_id, $id)
    {
        $res = DB::table('cart as c')
            ->select('buy_number', 'shop_price', 'c.goods_id')
            ->join('goods as g', 'c.goods_id', '=', 'g.goods_id')
            ->where(['id' => $id])
            ->whereIn('c.goods_id', $goods_id)
            ->get();
        $count = 0;
        foreach ($res as $k => $v) {
            $count += $v->buy_number * $v->shop_price;
        }
        session(['count' => $count]);
        return $count;
    }
    //获取商品详情信息
    public function getOrderDetail($goods_id, $id)
    {
        $where = [
            'is_del' => 1,
            'id' => $id
        ];
        //根据购物车表的商品id，和登入的用户id，去商品表查询商品的信息
        $cartInfo = DB::table('cart')
            ->join('goods', 'cart.goods_id', '=', 'goods.goods_id')
            ->where($where)
            ->whereIn('cart.goods_id', $goods_id)
            ->select('goods.goods_id', 'goods_name', 'goods_img', 'shop_price', 'buy_number')
            ->get();
        return $cartInfo;
    }
    //
    //提交订单页面
    public function index()
    {
        $user_id = session('user');
        $order_id = session('order_id');
        $where = [
            'id' => $user_id,
            'order_id' => $order_id,
        ];
        $res = DB::table('order')->where($where)->first();
        // dd($res);
        return view('success/success', ['res' => $res]);
    }
    //支付宝支付
    //电脑端支付
    public function pcpay(Request $request)
    {
        $user_id = session('user');
        $order_id = session('order_id');
        $where = [
            'id' => $user_id,
            'order_id' => $order_id,
        ];

        $res = DB::table('order')->where($where)->first();

        require_once app_path('libs/alipay/pagepay/service/AlipayTradeService.php');

        require_once app_path('libs/alipay/pagepay/buildermodel/AlipayTradePagePayContentBuilder.php');

        //商户订单号，商户网站订单系统中唯一订单号，必填
        $out_trade_no = $res->order_no;

        //订单名称，必填
        $subject = '商品';

        //付款金额，必填
        $total_amount = $res->order_amount;

        //商品描述，可空
        $body =  '';

        //构造参数
        $payRequestBuilder = new \AlipayTradePagePayContentBuilder();
        $payRequestBuilder->setBody($body);
        $payRequestBuilder->setSubject($subject);
        $payRequestBuilder->setTotalAmount($total_amount);
        $payRequestBuilder->setOutTradeNo($out_trade_no);
        /**
         * pagePay 电脑网站支付请求
         * @param $builder 业务参数，使用buildmodel中的对象生成。
         * @param $return_url 同步跳转地址，公网可以访问
         * @param $notify_url 异步通知地址，公网可以访问
         * @return $response 支付宝返回的信息
         */
        $aop = new \AlipayTradeService(config('alipay'));
        $response = $aop->pagePay($payRequestBuilder, config('alipay.return_url'), config('alipay.notify_url'));
        //输出表单
        var_dump($response);
        echo '支付';

    }
    //同步通知
    public function returnurl(Request $request)
    {
        /* *
            * 功能：支付宝页面跳转同步通知页面
            * 版本：2.0
            * 修改日期：2017-05-01
            * 说明：
            * 以下代码只是为了方便商户测试而提供的样例代码，商户可以根据自己网站的需要，按照技术文档编写,并非一定要使用该代码。

            *************************页面功能说明*************************
            * 该页面可在本机电脑测试
            * 可放入HTML等美化页面的代码、商户业务逻辑程序代码
        */

        $config = config('alipay');
        require_once app_path('libs/alipay/pagepay/service/AlipayTradeService.php');
        $arr = $_GET;
        $alipaySevice = new \AlipayTradeService($config);
        $result = $alipaySevice->check($arr);
        /* 实际验证过程建议商户添加以下校验。
        1、商户需要验证该通知数据中的out_trade_no是否为商户系统中创建的订单号，
        2、判断total_amount是否确实为该订单的实际金额（即商户订单创建时的金额），
        3、校验通知中的seller_id（或者seller_email) 是否为out_trade_no这笔单据的对应的操作方（有的时候，一个商户可能有多个seller_id/seller_email）
        4、验证app_id是否为该商户本身。
        */
        if ($result) { //验证成功
            /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
            //请在这里加上商户的业务逻辑程序代码

            //——请根据您的业务逻辑来编写程序（以下代码仅作参考）——
            //获取支付宝的通知返回参数，可参考技术文档中页面跳转同步通知参数列表

            //商户订单号
            $where['order_no'] = htmlspecialchars($_GET['out_trade_no']);
            $where['order_amount'] = htmlspecialchars($_GET['total_amount']);

            //支付宝交易号
            $trade_no = htmlspecialchars($_GET['trade_no']);
            $res = DB::table('order')->where($where)->count();
            if (!$res) {
                echo "支付宝交易号：" . $trade_no . "订单号" . $where['order_no'] . "订单金额" . $where['order_amount'] . "此订单有问题";
                exit;
            }
            //判断商户的id是否与付款的商户id一致
            if (config('alipay.seller_id') != htmlspecialchars($_GET['seller_id'])) {
                echo "支付宝交易号：" . $trade_no . "订单号" . $where['order_no'] . "订单金额" . $where['order_amount'] . "此订单有问题商户id不匹配";
                exit;
            }
            //判断用户的id是否与付款的用户id一致
            if (config('alipay.app_id') != htmlspecialchars($_GET['app_id'])) {
                echo "验证成功<br />支付宝交易号：" . $trade_no . "订单号" . $where['order_no'] . "订单金额" . $where['order_amount'] . "此订单有问题用户id不匹配";
                exit;
            }
            echo "验证成功,支付宝交易账户" . $trade_no;
            return redirect('Goods/index');
            //——请根据您的业务逻辑来编写程序（以上代码仅作参考）——
            /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        } else {
            //验证失败
            echo "验证失败";
        }
    }
    //异步通知
    public function  notifyurl(Request $request)
    {
        /* *
    * 功能：支付宝服务器异步通知页面
    * 版本：2.0
    * 修改日期：2017-05-01
    * 说明：
    * 以下代码只是为了方便商户测试而提供的样例代码，商户可以根据自己网站的需要，按照技术文档编写,并非一定要使用该代码。

    *************************页面功能说明*************************
    * 创建该页面文件时，请留心该页面文件中无任何HTML代码及空格。
    * 该页面不能在本机电脑测试，请到服务器上做测试。请确保外部可以访问该页面。
    * 如果没有收到该页面返回的 success 信息，支付宝会在24小时内按一定的时间策略重发通知
    */
        $config = config('alipay');


        require_once app_path('libs/alipay/pagepay/service/AlipayTradeService.php');

        $arr = $_POST;
        \Log::channel('alipay')->info("支付宝的异步通知".json_encode($arr));

        exit();

        $alipaySevice = new \AlipayTradeService(config('alipay'));
        $alipaySevice->writeLog(var_export($_POST, true));
        $result = $alipaySevice->check($arr);

        /* 实际验证过程建议商户添加以下校验。
        1、商户需要验证该通知数据中的out_trade_no是否为商户系统中创建的订单号，
        2、判断total_amount是否确实为该订单的实际金额（即商户订单创建时的金额），
        3、校验通知中的seller_id（或者seller_email) 是否为out_trade_no这笔单据的对应的操作方（有的时候，一个商户可能有多个seller_id/seller_email）
        4、验证app_id是否为该商户本身。
        */
        if ($result) { //验证成功
            /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
            //请在这里加上商户的业务逻辑程序代
            //——请根据您的业务逻辑来编写程序（以下代码仅作参考）——

            //获取支付宝的通知返回参数，可参考技术文档中服务器异步通知参数列表
            //商户订单号
            $where['order_no'] = htmlspecialchars($_POST['out_trade_no']);
            $where['order_amount'] = htmlspecialchars($_POST['total_amount']);

            //支付宝交易号
            $trade_no = htmlspecialchars( $_POST['trade_no']);
            $res = DB::table('order')->where($where)->count();
            if (!$res) {
                \Log::channel('alipay')->info( "支付宝交易号：" . $trade_no . "订单号" . $where['order_no'] . "订单金额" . $where['order_amount'] . "此订单有问题");
                exit;
            }
            //判断商户的id是否与付款的商户id一致
            if (config('alipay.seller_id') != htmlspecialchars( $_POST['seller_id'])) {
                \Log::channel('alipay')->info( "支付宝交易号：" . $trade_no . "订单号" . $where['order_no'] . "订单金额" . $where['order_amount'] . "此订单有问题商户id不匹配");

                exit;
            }
            //判断用户的id是否与付款的用户id一致
            if (config('alipay.app_id') != htmlspecialchars( $_POST['app_id'])) {
                \Log::channel('alipay')->info("支付宝交易号：" . $trade_no . "订单号" . $where['order_no'] . "订单金额" . $where['order_amount'] . "此订单有问题用户id不匹配");
                exit;
            }
            echo "验证成功,支付宝交易账户" . $trade_no;
            //交易状态
            $trade_status = $_POST['trade_status'];


            if ($_POST['trade_status'] == 'TRADE_FINISHED') {

                //判断该笔订单是否在商户网站中已经做过处理
                //如果没有做过处理，根据订单号（out_trade_no）在商户网站的订单系统中查到该笔订单的详细，并执行商户的业务程序
                //请务必判断请求时的total_amount与通知时获取的total_fee为一致的
                //如果有做过处理，不执行商户的业务程序

                //注意：
                //退款日期超过可退款期限后（如三个月可退款），支付宝系统发送该交易状态通知
            } else if ($_POST['trade_status'] == 'TRADE_SUCCESS') {
                //判断该笔订单是否在商户网站中已经做过处理
                //如果没有做过处理，根据订单号（out_trade_no）在商户网站的订单系统中查到该笔订单的详细，并执行商户的业务程序
                //请务必判断请求时的total_amount与通知时获取的total_fee为一致的
                //如果有做过处理，不执行商户的业务程序
                //注意：
                //付款完成后，支付宝系统发送该交易状态通知
            }
            //——请根据您的业务逻辑来编写程序（以上代码仅作参考）——
            echo "success";    //请不要修改或删除
        } else {
            //验证失败
            echo "fail";
        }
    }
}
