<?php
return[
    //应用ID,您的APPID。
    'app_id' => "2016092600603223",
    //商户的id
    'seller_id'=> '2088102177409872',

    //商户私钥
    'merchant_private_key' => "MIIEpAIBAAKCAQEA2aWiZhnxUpW6/If+WWFcMIj/vK/zXoW9eRna65HQeFwypzOHazvNG+NlDwjV05EOpQZg/Pv537wXf6QKCBPxQff7Shh7GYGr3wkPF8Hd1nUCHYAGl38sMgUVJkrCnYikuO8o9VAxj1PErkg4mP3uiAPvrzMx+bFsriu/LTYuNNZ3oH4rgxK1gc7zhUqRRfIPZvGOXlx0FJYeI/kzJOrliBVGm8FrQuXBueWRa3/pYcrCrrdwWNO3HdFCr3ab97blu/7YtbUX4Fx5t0BcIQevdzuYj+FVZIp+8qr7/1IhzHLnw9wq2PT9DKU14f7o+fPMuUr+MPkb6qflYCPCoDi6lQIDAQABAoIBAAbC8oyhebHLHQgDYY99StPnLaq6/KCPHxfICdkPqp5SnvA61ZYrQXAAXH9fEuWDuCTAUfsKCPU+bqx0eCtQE0qtXY0rvYdJVAGV0nY2e6HR+MLZ21qRhNn49nM+F1W1jQiBxY/5cdC2FYIklD2MhgCLvsJ+oKrVLD8s9L9+02iowlpkMPfvtZ5X/GPcfhkaDYm1lDyfXbapfQpSgO8HhWYSqN/Bp6N1WVdjkYpE+fIYPb47Gbuo34G6FwCJ/U3FOZBW0JctdfxXAvYAyC9BUHebPr9lTrFeOUZlzXiFtIVWGhkUk9pGdi+nYAqiDJb1VsP4QE785/QGtzHlqEVKnfECgYEA8yfR+VXFp1PHeTf8FXj+6WtdxJDcpkR8ODnAH4miiRLlor6ECwduujIOJB1m8KRehpjzlkxKbXJQ+iu0mRP8xhRbIrupqmdQzdFxB3CjKp7/LjMTd2L1UxbDdOkNr5Y2JuNIbvhhUbQZRh6mu7KpZvPDPnn1RF8lCzjYy/+xe78CgYEA5STdD/XkPzXf53XvLiVzEWRNIYqoP7oSZC2s2RJshP78eM9Xmu/mFIO0Gpc2HZJlt3/H4T8BmM8CdHGG704IqsZFivnJZTMCtNK68BhQb5rpot0MeRSUVgdGGSb33faJRcIQWPDiEi8b0VVwaoLmGCc5wdKGPQXlBlX1sVpBbqsCgYEA0CdLif3SKW7/+RZjF2KwHdRKviYgrbk8+cj+XkEtwdhKeupLEUH4mS6WinYdFG4xeVngVM7UfCL7jV3yyYShxFPSvMYX9YdiVf8wmmTjWW4pFzykMnSlfM6k3iVQEPsvOlUhQdYhQZdCd5Nvuoi0miyc/JPc0f39MUEl3mTyY/MCgYAjUTQ1QovkZjRVY2ry7Ni4ZIIy2kEmWVJ+WD/c0ScitH5LTIHPIoyOuuHme0ne8vJ0fuBayjA/0iApvFm0xo01bQvI6MjurRcMsbIYUVcMbI74CCPlAZP/Um2ucMAhWN89fjSw0Ke3b8VU1VtXP0ehj2Iqin0gKKls+ZC9vhB5swKBgQDfABFoMCb6F41utByzuCQKvzN2CtKUhW2Y1D0G+bkBsPRg2cv/rMbW/TeOojbrP2gIQqiN8zAllGwWIEwLRBxRkQzr7yuIe5DcQf4CelhAqlfokR0NY3oXP72V8KfG5Af0IdZPA4EaUHWQia30RqZ/0dTBYiIVPy8b2YfKfTNsxA==",


    //异步通知地址
    'notify_url' => "http:http://www.ad.com/Success/notifyurl",

    //同步跳转
    'return_url' => "http:http://www.ad.com/Success/returnurl",


    //编码格式
    'charset' => "UTF-8",


    //签名方式
    'sign_type'=>"RSA2",


    //支付宝网关
    'gatewayUrl' => "https://openapi.alipaydev.com/gateway.do",


    //支付宝公钥,查看地址：https://openhome.alipay.com/platform/keyManage.htm 对应APPID下的支付宝公钥。
    'alipay_public_key' => "MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAp/b0hnILrhD/UBr1NYlhNE1voGs1znn6ue5e+3qmoFGNjwZ6jbgUpinwNY5D29OMwevesKku7eYyeAyqud/C/7h8MoDNbhy0wwoHRw0NeHuMbObg7AQA8Pzf4wdNGNcgUHmJuX2AWKoJbC0fZY20S6hLy7oz/2MY3jlhxqCY5iV9z1H9D88VPE/bX/BDCV5lS9Z5ppebYNbMEnOGhck3/cEBO9PVou/jw4NlMYg6Ou+why5BhSGIxGo5dogxSNEr1VTDElDsnCDpJPvWrPmpR5iJYPKUUEBlCgOg3aU66xiWWuTlrmVEAb3thIow8V8AcKi1kHfGz0sF1a9itXvOXQIDAQAB",
];