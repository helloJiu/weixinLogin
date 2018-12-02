<!DOCTYPE html>
<html>
<head>
    <title>哈哈</title>
    <link rel="stylesheet" type="text/css" href="/css/reset.css">
    <link rel="stylesheet" type="text/css" href="/css/index.css">
    <script type="text/javascript" src="/vendor/jquery.min.js"></script>
    <script type="text/javascript" src="/vendor/vue.min.js"></script>
    <script type="text/javascript" src="/js/tools.js"></script>
    <script type="text/javascript" src="/component/login.js"></script>
    <script type="text/javascript" src="/vendor/layer/layer.js"></script>
</head>
<body>
    <div id="app" v-cloak>
        <header>
                @if(session()->has('user'))
                    <div class="userinfo"><span>你好，{{ session()->get('user')['nickname'] }}</span><img src="{{ session()->get('user')['headimgurl'] }}"></div>
                @else
                    <login></login>
                @endif
        </header>
    </div>
</body>
<script type="text/javascript" src="/js/index.js"></script>
</html>